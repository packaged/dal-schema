<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\Enum\AbstractEnum;

class CassandraKeyType extends AbstractEnum
{
  const PRIMARY = 'primary';
  const INDEX = 'index';
}
