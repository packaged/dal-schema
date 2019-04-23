<?php
namespace Packaged\DalSchema\Engines\Mysql;

use Packaged\DalSchema\Schema\AbstractTypedColumn;

class MySQLColumn extends AbstractTypedColumn
{
  const EXTRA_AUTO_INCREMENT = 'AUTO_INCREMENT';

  protected $_size;
  protected $_isNullable = false;
  protected $_defaultValue = null;
  protected $_extra;
  protected $_collation;
  protected $_signed;

  public function __construct(
    string $name, MySQLColumnType $type, int $size = null, bool $nullable = false, $default = null, $extra = null,
    $collation = null
  )
  {
    $this->_setName($name);
    $this->setSize($size);
    $this->setNullable($nullable);
    $this->setDefaultValue($default);
    $this->setExtra($extra);
    $this->setCollation($collation);
    $this->_setType($type->getValue());

    if($type == MySQLColumnType::ID)
    {
      $this->setExtra(self::EXTRA_AUTO_INCREMENT);
    }
    $this->_autoSign();
    $this->_autoSize();
    $this->_autoCollate();
  }

  protected function _autoSize()
  {
    switch($this->getType())
    {
      case MySQLColumnType::AMOUNT:
      case MySQLColumnType::DECIMAL:
        $this->setSize([10, 2], true);
        break;
      case MySQLColumnType::BIG_INT:
      case MySQLColumnType::BIG_INT_UNSIGNED:
        $this->setSize(20, true);
        break;
      case MySQLColumnType::INT_UNSIGNED:
      case MySQLColumnType::INT_SIGNED:
      case MySQLColumnType::ID:
      case MySQLColumnType::DATE:
        $this->setSize(10, true);
        break;
      case MySQLColumnType::TINY_INT_UNSIGNED:
      case MySQLColumnType::TINY_INT:
      case MySQLColumnType::STATUS:
        $this->setSize(3, true);
        break;
      case MySQLColumnType::SMALL_INT_UNSIGNED:
      case MySQLColumnType::SMALL_INT:
        $this->setSize(6, true);
        break;
      case MySQLColumnType::BOOL:
        $this->setSize(1, true);
        break;
      case MySQLColumnType::FID:
        $this->setSize(64, true);
        break;
      case MySQLColumnType::VARCHAR:
      case MySQLColumnType::VARCHAR_MB4:
        $this->setSize(255, true);
        break;
    }
  }

  protected function _autoSign()
  {
    switch($this->getType())
    {
      case MySQLColumnType::DATE:
      case MySQLColumnType::INT_UNSIGNED:
      case MySQLColumnType::MEDIUM_INT_UNSIGNED:
      case MySQLColumnType::BIG_INT_UNSIGNED:
      case MySQLColumnType::SMALL_INT_UNSIGNED:
      case MySQLColumnType::STATUS:
      case MySQLColumnType::TINY_INT_UNSIGNED:
      case MySQLColumnType::ID:
        $this->setSigned(false);
        break;
    }
  }

  protected function _autoCollate()
  {
    switch($this->getType())
    {
      case MySQLColumnType::FID:
        $this->setCollation('utf8_bin', true);
        break;
      case MySQLColumnType::VARCHAR:
        $this->setCollation('utf8_unicode_ci', true);
        break;
      case MySQLColumnType::TEXT:
      case MySQLColumnType::MEDIUMTEXT:
      case MySQLColumnType::LONGTEXT:
        $this->setCollation('utf8_unicode_ci', true);
        break;
      case MySQLColumnType::VARCHAR_MB4:
      case MySQLColumnType::TEXT_MB4:
      case MySQLColumnType::MEDIUMTEXT_MB4:
      case MySQLColumnType::LONGTEXT_MB4:
        $this->setCollation('utf8mb4_unicode_ci', true);
        break;
    }
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
   * @param bool           $onlySetIfNull
   *
   * @return $this
   */
  public function setSize($size, bool $onlySetIfNull = false)
  {
    if($onlySetIfNull && $this->_size !== null)
    {
      return $this;
    }

    if($size === null)
    {
      $this->_size = null;
    }
    else if(is_string($size) && strpos($size, ',') > 0)
    {
      $this->_size = explode(',', $size);
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
  public function getExtra()
  {
    return $this->_extra;
  }

  /**
   * @param string $extra
   *
   * @return $this
   */
  public function setExtra($extra)
  {
    $this->_extra = $extra;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getCollation(): ?string
  {
    return $this->_collation;
  }

  /**
   * @param string $collation
   *
   * @param bool   $onlySetIfNull
   *
   * @return $this
   */
  public function setCollation(string $collation = null, bool $onlySetIfNull = false)
  {
    if($onlySetIfNull && $this->_collation !== null)
    {
      return $this;
    }
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
