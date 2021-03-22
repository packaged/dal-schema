<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;
use function strtoupper;

/**
 * Class MySQLKeyType
 *
 * @method static MySQLKeyType PRIMARY
 * @method static MySQLKeyType UNIQUE
 * @method static MySQLKeyType INDEX
 * @method static MySQLKeyType FULLTEXT
 *
 * todo: constraint
 * todo: foreign key
 */
class MySQLKeyType extends AbstractEnum
{
  private const PRIMARY  = 'primary key';
  private const UNIQUE   = 'unique';
  private const INDEX    = 'index';
  private const FULLTEXT = 'fulltext';

  public function toUpper()
  {
    return strtoupper($this->getValue());
  }
}
