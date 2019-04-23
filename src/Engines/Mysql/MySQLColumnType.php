<?php
namespace Packaged\DalSchema\Engines\Mysql;

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

  public static function isInt($type)
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
}
