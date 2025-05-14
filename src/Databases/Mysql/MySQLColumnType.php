<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;
use function str_replace;

/**
 * Class MySQLColumnType
 * @method static MySQLColumnType DATE
 * @method static MySQLColumnType DATETIME
 * @method static MySQLColumnType SQL_DATE
 * @method static MySQLColumnType ID
 * @method static MySQLColumnType FID
 * @method static MySQLColumnType VARCHAR
 * @method static MySQLColumnType VARCHAR_MB4
 * @method static MySQLColumnType TEXT
 * @method static MySQLColumnType MEDIUMTEXT
 * @method static MySQLColumnType LONGTEXT
 * @method static MySQLColumnType TEXT_MB4
 * @method static MySQLColumnType MEDIUMTEXT_MB4
 * @method static MySQLColumnType LONGTEXT_MB4
 * @method static MySQLColumnType BOOL
 * @method static MySQLColumnType TINY_INT
 * @method static MySQLColumnType TINY_INT_UNSIGNED
 * @method static MySQLColumnType STATUS
 * @method static MySQLColumnType BIG_INT
 * @method static MySQLColumnType BIG_INT_UNSIGNED
 * @method static MySQLColumnType SMALL_INT
 * @method static MySQLColumnType SMALL_INT_UNSIGNED
 * @method static MySQLColumnType INT_UNSIGNED
 * @method static MySQLColumnType INT_SIGNED
 * @method static MySQLColumnType MEDIUM_INT_UNSIGNED
 * @method static MySQLColumnType MEDIUM_INT_SIGNED
 * @method static MySQLColumnType AMOUNT
 * @method static MySQLColumnType DECIMAL
 * @method static MySQLColumnType DECIMAL_UNSIGNED
 * @method static MySQLColumnType BLOB
 * @method static MySQLColumnType LONGBLOB
 * @method static MySQLColumnType MONEY
 */
class MySQLColumnType extends AbstractEnum
{
  private const DATE = 'date';
  private const DATETIME = 'datetime';
  private const SQL_DATE = 'realdate';
  private const ID = 'id';
  private const FID = 'fid';
  private const VARCHAR = 'varchar';
  private const VARCHAR_MB4 = 'varchar_mb4';
  private const TEXT = 'text';
  private const MEDIUMTEXT = 'mediumtext';
  private const LONGTEXT = 'longtext';
  private const TEXT_MB4 = 'text_mb4';
  private const MEDIUMTEXT_MB4 = 'mediumtext_mb4';
  private const LONGTEXT_MB4 = 'longtext_mb4';
  private const BOOL = 'bool';
  private const TINY_INT = 'tinyint';
  private const TINY_INT_UNSIGNED = 'tinyintunsigned';
  private const STATUS = 'status';
  private const BIG_INT = 'bigint';
  private const BIG_INT_UNSIGNED = 'bigintunsigned';
  private const SMALL_INT = 'smallint';
  private const SMALL_INT_UNSIGNED = 'smallintunsigned';
  private const INT_UNSIGNED = 'intunsigned';
  private const INT_SIGNED = 'int';
  private const MEDIUM_INT_UNSIGNED = 'mediumintunsigned';
  private const MEDIUM_INT_SIGNED = 'mediumint';
  private const AMOUNT = 'amount';
  private const DECIMAL = 'decimal';
  private const DECIMAL_UNSIGNED = 'decimalunsigned';
  private const BLOB = 'blob';
  private const LONGBLOB = 'longblob';
  private const MONEY = 'money';

  public function getType()
  {
    switch($this->getValue())
    {
      case self::FID:
        return self::VARCHAR;
      default:
        return str_replace(['unsigned', '_mb4'], '', $this->getValue());
    }
  }

  public function isInt(): bool
  {
    switch($this->getValue())
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
      case self::DECIMAL_UNSIGNED:
        return [10, 2];
      case self::BIG_INT:
      case self::BIG_INT_UNSIGNED:
        return 20;
      case self::INT_UNSIGNED:
      case self::INT_SIGNED:
      case self::ID:
        return 10;
      case self::TINY_INT_UNSIGNED:
      case self::TINY_INT:
      case self::STATUS:
        return 3;
      case self::SMALL_INT_UNSIGNED:
      case self::SMALL_INT:
        return 6;
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
      case self::TEXT:
      case self::MEDIUMTEXT:
      case self::LONGTEXT:
        return new MySQLCollation(MySQLCollation::UTF8MB3_UNICODE_CI);
      case self::VARCHAR_MB4:
      case self::TEXT_MB4:
      case self::MEDIUMTEXT_MB4:
      case self::LONGTEXT_MB4:
        return new MySQLCollation(MySQLCollation::UTF8MB4_UNICODE_CI);
    }
    return null;
  }

  public function defaultCharacterSet(): ?MySQLCharacterSet
  {
    switch($this->getValue())
    {
      case self::FID:
      case self::VARCHAR:
      case self::TEXT:
      case self::MEDIUMTEXT:
      case self::LONGTEXT:
        return new MySQLCharacterSet(MySQLCharacterSet::UTF8MB3);
      case self::VARCHAR_MB4:
      case self::TEXT_MB4:
      case self::MEDIUMTEXT_MB4:
      case self::LONGTEXT_MB4:
        return new MySQLCharacterSet(MySQLCharacterSet::UTF8MB4);
    }
    return null;
  }
}
