<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Exception;
use Packaged\DalSchema\Abstracts\AbstractColumn;
use Packaged\DalSchema\Writer;
use Packaged\Helpers\ValueAs;

class MySQLColumn extends AbstractColumn
{
  const EXTRA_AUTO_INCREMENT = 'auto_increment';

  protected $_size;
  protected $_isNullable = false;
  protected $_defaultValue = null;
  protected $_extra;
  protected $_characterSet;
  protected $_collation;
  protected $_signed;

  public function __construct(
    string            $name, MySQLColumnType $type, $size = null, bool $nullable = false, $default = null,
                      $extra = null,
    MySQLCharacterSet $characterSet = null,
    MySQLCollation    $collation = null
  )
  {
    $this->_setName($name);
    $this->_setType($type);
    if($type->is(MySQLColumnType::ID()))
    {
      $this->setExtra(self::EXTRA_AUTO_INCREMENT);
    }
    $this->setSigned($type->isSigned());
    $this->setSize($size ?: $type->defaultSize());
    $this->setCharacterSet($characterSet ?: $type->defaultCharacterSet());
    $this->setCollation($collation ?: $type->defaultCollation());
    $this->setNullable($nullable);
    $this->setDefaultValue($default);
    $this->setExtra($extra);
  }

  private $_type;

  protected function _setType(MySQLColumnType $type)
  {
    $this->_type = $type;
    return $this;
  }

  public function getType(): MySQLColumnType
  {
    return $this->_type;
  }

  /**
   * @return int|int[]
   */
  public function getSize()
  {
    switch($this->_type)
    {
      case MySQLColumnType::BOOL():
      case MySQLColumnType::DATE():
      case MySQLColumnType::DATETIME():
      case MySQLColumnType::SQL_DATE():
        return 0;
    }
    return $this->_size;
  }

  /**
   * @param int|int[]|null $size
   *
   * @return $this
   */
  public function setSize($size)
  {
    if($size === null)
    {
      $this->_size = null;
    }
    else
    {
      $this->_size = array_map(function ($i) { return (int)$i; }, ValueAs::arr($size));
    }
    return $this;
  }

  /**
   * @return bool
   */
  public function isNullable(): bool
  {
    return $this->_isNullable;
  }

  /**
   * @param bool $isNullable
   *
   * @return $this
   */
  public function setNullable(bool $isNullable)
  {
    $this->_isNullable = $isNullable;
    return $this;
  }

  /**
   * @return string|int|bool
   */
  public function getDefaultValue()
  {
    return $this->_defaultValue;
  }

  /**
   * @param string|int|bool $defaultValue
   *
   * @return $this
   */
  public function setDefaultValue($defaultValue)
  {
    $this->_defaultValue = $defaultValue;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getExtra(): ?string
  {
    return $this->_extra;
  }

  /**
   * @param string $extra
   *
   * @return $this
   */
  public function setExtra(?string $extra)
  {
    $this->_extra = $extra ?: null;
    return $this;
  }

  /**
   * @return MySQLCharacterSet|null
   */
  public function getCharacterSet(): ?MySQLCharacterSet
  {
    return $this->_characterSet;
  }

  /**
   * @param MySQLCharacterSet $characterSet
   *
   * @return $this
   */
  public function setCharacterSet(MySQLCharacterSet $characterSet = null)
  {
    $this->_characterSet = $characterSet;
    return $this;
  }

  /**
   * @return MySQLCollation|null
   */
  public function getCollation(): ?MySQLCollation
  {
    return $this->_collation;
  }

  /**
   * @param MySQLCollation $collation
   *
   * @return $this
   */
  public function setCollation(MySQLCollation $collation = null)
  {
    $this->_collation = $collation;
    return $this;
  }

  /**
   * @return bool
   */
  public function isSigned(): bool
  {
    return $this->_signed;
  }

  /**
   * @param bool $signed
   *
   * @return $this
   */
  public function setSigned(bool $signed)
  {
    $this->_signed = $signed;
    return $this;
  }

  public function writerCreate(): string
  {
    $definition = ['`' . $this->getName() . '`'];

    $size = $this->getSize();
    $definition[] = $this->getType()->getType() . ($size ? '(' . implode(',', $size) . ')' : '');

    if(!$this->getType()->isSigned())
    {
      $definition[] = 'unsigned';
    }

    $charSet = $this->getCharacterSet();
    if($charSet)
    {
      $definition[] = 'CHARACTER SET ' . $charSet;
    }

    $collate = $this->getCollation();
    if($collate)
    {
      $definition[] = 'COLLATE ' . $collate->getValue();
    }

    if(!$this->isNullable())
    {
      $definition[] = 'NOT NULL';
    }

    if($this->getDefaultValue())
    {
      $definition[] = 'DEFAULT ' . $this->getDefaultValue();
    }

    $extra = $this->getExtra();
    if($extra)
    {
      $definition[] = $extra;
    }

    return implode(' ', $definition);
  }

  /**
   * @param Writer $old
   *
   * @return string
   * @throws Exception
   */
  public function writerAlter(Writer $old): string
  {
    if(!$old instanceof static)
    {
      throw new Exception('unexpected type provided to alter');
    }

    if($this->getName() !== $old->getName()
      || $this->getSize() !== $old->getSize()
      || $this->getType()->getType() !== $old->getType()->getType()
      || $this->getType()->isSigned() !== $old->getType()->isSigned()
      || (string)$this->getCharacterSet() !== (string)$old->getCharacterSet()
      || (string)$this->getCollation() !== (string)$old->getCollation()
      || $this->isNullable() !== $old->isNullable()
      || $this->getDefaultValue() !== $old->getDefaultValue()
      || $this->getExtra() !== $old->getExtra()
    )
    {
      return 'CHANGE COLUMN `' . $old->getName() . '` ' . $this->writerCreate();
    }
    return '';
  }
}
