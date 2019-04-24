<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;

class MySQLEngine extends AbstractEnum
{
  const INNODB = 'InnoDB';
  const MRG_MYISAM = 'MRG_MYISAM';
  const MEMORY = 'MEMORY';
  const BLACKHOLE = 'BLACKHOLE';
  const MYISAM = 'MyISAM';
  const CSV = 'CSV';
  const ARCHIVE = 'ARCHIVE';
  const PERFORMANCE_SCHEMA = 'PERFORMANCE_SCHEMA';
  const FEDERATED = 'FEDERATED';
}
