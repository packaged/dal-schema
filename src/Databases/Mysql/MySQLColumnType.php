<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;

class MySQLColumnType extends AbstractEnum
{
  const DATE = 'date';
  const SQL_DATE = 'realdate';
  const ID = 'id';
  const FID = 'fid';
  const VARCHAR = 'varchar';
  const VARCHAR_MB4 = 'varchar_mb4';
  const TEXT = 'text';
  const MEDIUMTEXT = 'mediumtext';
  const LONGTEXT = 'longtext';
  const TEXT_MB4 = 'text_mb4';
  const MEDIUMTEXT_MB4 = 'mediumtext_mb4';
  const LONGTEXT_MB4 = 'longtext_mb4';
  const BOOL = 'bool';
  const TINY_INT = 'tinyint';
  const TINY_INT_UNSIGNED = 'tinyintunsigned';
  const STATUS = 'status';
  const BIG_INT = 'bigint';
  const BIG_INT_UNSIGNED = 'bigintunsigned';
  const SMALL_INT = 'smallint';
  const SMALL_INT_UNSIGNED = 'smallintunsigned';
  const INT_UNSIGNED = 'intunsigned';
  const INT_SIGNED = 'int';
  const MEDIUM_INT_UNSIGNED = 'mediumintunsigned';
  const MEDIUM_INT_SIGNED = 'mediumint';
  const AMOUNT = 'amount';
  const DECIMAL = 'decimal';
  const BLOB = 'blob';
  const LONGBLOB = 'longblob';
  const MONEY = 'money';

  public static function isInt($type): bool
  {
    switch($type)
    {
      case self::DATE:
      case self::INT_UNSIGNED:
      case self::ID:
      case self::INT_SIGNED:
      case self::MEDIUM_INT_UNSIGNED:
      case self::MEDIUM_INT_SIGNED:
      case self::BIG_INT_UNSIGNED:
      case self::MONEY:
      case self::BIG_INT:
      case self::SMALL_INT_UNSIGNED:
      case self::SMALL_INT:
      case self::TINY_INT_UNSIGNED:
      case self::TINY_INT:
      case self::STATUS:
      case self::BOOL:
        return true;
      default:
        return false;
    }
  }

  public function isSigned(): bool
  {
    switch($this->getValue())
    {
      case self::DATE:
      case self::INT_UNSIGNED:
      case self::MEDIUM_INT_UNSIGNED:
      case self::BIG_INT_UNSIGNED:
      case self::SMALL_INT_UNSIGNED:
      case self::STATUS:
      case self::TINY_INT_UNSIGNED:
      case self::ID:
        return false;
    }
    return true;
  }

  /**
   * @return array|int|null
   */
  public function defaultSize()
  {
    switch($this->getValue())
    {
      case self::AMOUNT:
      case self::DECIMAL:
        return [10, 2];
      case self::BIG_INT:
      case self::BIG_INT_UNSIGNED:
        return 20;
      case self::INT_UNSIGNED:
      case self::INT_SIGNED:
      case self::ID:
      case self::DATE:
        return 10;
      case self::TINY_INT_UNSIGNED:
      case self::TINY_INT:
      case self::STATUS:
        return 3;
      case self::SMALL_INT_UNSIGNED:
      case self::SMALL_INT:
        return 6;
      case self::BOOL:
        return 1;
      case self::FID:
        return 64;
      case self::VARCHAR:
      case self::VARCHAR_MB4:
        return 255;
    }
    return null;
  }

  public function defaultCollation(): ?MySQLCollation
  {
    switch($this->getValue())
    {
      case self::FID:
        return new MySQLCollation(MySQLCollation::UTF8_BIN);
      case self::VARCHAR:
        return new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI);
      case self::TEXT:
      case self::MEDIUMTEXT:
      case self::LONGTEXT:
        return new MySQLCollation(MySQLCollation::UTF8_UNICODE_CI);
      case self::VARCHAR_MB4:
      case self::TEXT_MB4:
      case self::MEDIUMTEXT_MB4:
      case self::LONGTEXT_MB4:
        return new MySQLCollation(MySQLCollation::UTF8MB4_UNICODE_CI);
    }
    return null;
  }
}
