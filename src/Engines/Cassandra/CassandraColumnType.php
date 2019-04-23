<?php
namespace Packaged\DalSchema\Engines\Cassandra;

use Packaged\Enum\AbstractEnum;

class CassandraColumnType extends AbstractEnum
{
  const ASCII = 'ascii';
  const BIGINT = 'bigint';
  const BLOB = 'blob';
  const BOOLEAN = 'boolean';
  const COUNTER = 'counter';
  const DECIMAL = 'decimal';
  const DOUBLE = 'double';
  const FLOAT = 'float';
  const FROZEN = 'frozen';
  const INET = 'inet';
  const INT = 'int';
  const LIST = 'list';
  const MAP = 'map';
  const SET = 'set';
  const TEXT_SET = 'set<text>';
  const TEXT_LIST = 'list<text>';
  const TEXT = 'text';
  const TIMESTAMP = 'timestamp';
  const TIMEUUID = 'timeuuid';
  const TUPLE = 'tuple';
  const UUID = 'uuid';
  const VARCHAR = 'varchar';
  const VARINT = 'varint';
}
