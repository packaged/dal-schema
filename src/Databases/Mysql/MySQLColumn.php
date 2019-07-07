<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Abstracts\AbstractColumn;

class MySQLColumn extends AbstractColumn
{
  const EXTRA_AUTO_INCREMENT = 'AUTO_INCREMENT';

  protected $_size;
  protected $_isNullable = false;
  protected $_defaultValue = null;
  protected $_extra;
  protected $_characterSet;
  protected $_collation;
  protected $_signed;

  public function __construct(
    string $name, MySQLColumnType $type, $size = null, bool $nullable = false, $default = null, $extra = null,
    MySQLCharacterSet $characterSet = null,
    MySQLCollation $collation = null
  )
  {
    $this->_setName($name);
    $this->_setType($type);
    if($type->is(MySQLColumnType::ID))
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
   * @return int|array
   */
  public function getSize()
  {
    return $this->_size;
  }

  /**
   * @param int|array|null $size
   *
   * @return $this
   */
  public function setSize($size)
  {
    if($size === null)
    {
      $this->_size = null;
    }
    else if(is_string($size) && strpos($size, ',') > 0)
    {
      $this->_size = array_map(function ($i) { return (int)$i; }, explode(',', $size));
    }
    else
    {
      $this->_size = is_array($size) ? $size : [$size];
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
    $this->_extra = $extra;
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
}
