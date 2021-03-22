<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\Enum\AbstractEnum;

/**
 * @method static CassandraKeyType PRIMARY
 * @method static CassandraKeyType INDEX
 */
class CassandraKeyType extends AbstractEnum
{
  const PRIMARY = 'primary';
  const INDEX   = 'index';
}
