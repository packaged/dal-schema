<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;

class MySQLKeyType extends AbstractEnum
{
  const PRIMARY = 'primary';
  const UNIQUE = 'unique';
  const INDEX = 'index';
  const FULLTEXT = 'fulltext';
  const CONSTRAINT = 'constraint';
}
