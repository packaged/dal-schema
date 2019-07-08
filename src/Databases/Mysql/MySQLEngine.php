<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;

/**
 * Class MySQLEngine
 * @method static MySQLEngine INNODB
 * @method static MySQLEngine MRG_MYISAM
 * @method static MySQLEngine MEMORY
 * @method static MySQLEngine BLACKHOLE
 * @method static MySQLEngine MYISAM
 * @method static MySQLEngine CSV
 * @method static MySQLEngine ARCHIVE
 * @method static MySQLEngine PERFORMANCE_SCHEMA
 * @method static MySQLEngine FEDERATED
 */
class MySQLEngine extends AbstractEnum
{
  private const INNODB = 'InnoDB';
  private const MRG_MYISAM = 'MRG_MYISAM';
  private const MEMORY = 'MEMORY';
  private const BLACKHOLE = 'BLACKHOLE';
  private const MYISAM = 'MyISAM';
  private const CSV = 'CSV';
  private const ARCHIVE = 'ARCHIVE';
  private const PERFORMANCE_SCHEMA = 'PERFORMANCE_SCHEMA';
  private const FEDERATED = 'FEDERATED';
}
