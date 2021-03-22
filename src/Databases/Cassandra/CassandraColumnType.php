<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\Enum\AbstractEnum;

/**
 * Class MySQLKeyType
 *
 * @method static CassandraColumnType ASCII
 * @method static CassandraColumnType BIGINT
 * @method static CassandraColumnType BLOB
 * @method static CassandraColumnType BOOLEAN
 * @method static CassandraColumnType COUNTER
 * @method static CassandraColumnType DECIMAL
 * @method static CassandraColumnType DOUBLE
 * @method static CassandraColumnType FLOAT
 * @method static CassandraColumnType FROZEN
 * @method static CassandraColumnType INET
 * @method static CassandraColumnType INT
 * @method static CassandraColumnType LIST
 * @method static CassandraColumnType MAP
 * @method static CassandraColumnType SET
 * @method static CassandraColumnType TEXT_SET
 * @method static CassandraColumnType TEXT_LIST
 * @method static CassandraColumnType TEXT
 * @method static CassandraColumnType TIMESTAMP
 * @method static CassandraColumnType TIMEUUID
 * @method static CassandraColumnType TUPLE
 * @method static CassandraColumnType UUID
 * @method static CassandraColumnType VARCHAR
 * @method static CassandraColumnType VARINT
 */
class CassandraColumnType extends AbstractEnum
{
  const ASCII     = 'ascii';
  const BIGINT    = 'bigint';
  const BLOB      = 'blob';
  const BOOLEAN   = 'boolean';
  const COUNTER   = 'counter';
  const DECIMAL   = 'decimal';
  const DOUBLE    = 'double';
  const FLOAT     = 'float';
  const FROZEN    = 'frozen';
  const INET      = 'inet';
  const INT       = 'int';
  const LIST      = 'list';
  const MAP       = 'map';
  const SET       = 'set';
  const TEXT_SET  = 'set<text>';
  const TEXT_LIST = 'list<text>';
  const TEXT      = 'text';
  const TIMESTAMP = 'timestamp';
  const TIMEUUID  = 'timeuuid';
  const TUPLE     = 'tuple';
  const UUID      = 'uuid';
  const VARCHAR   = 'varchar';
  const VARINT    = 'varint';
}
