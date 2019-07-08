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
 * @method static MySQLKeyType CONSTRAINT
 */
class MySQLKeyType extends AbstractEnum
{
  private const PRIMARY = 'primary';
  private const UNIQUE = 'unique';
  private const INDEX = 'index';
  private const FULLTEXT = 'fulltext';
  private const CONSTRAINT = 'constraint';

  public function toUpper()
  {
    return strtoupper($this->getValue());
  }
}
