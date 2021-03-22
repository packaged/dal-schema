<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Database;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLKey;
use Packaged\DalSchema\Abstracts\AbstractTable;
use Packaged\Helpers\Arrays;

class CassandraTable extends AbstractTable
{

  protected $_columns;
  protected $_keys;

  /**
   * MySQLTable constructor.
   *
   * @param string        $name
   * @param string        $description
   * @param MySQLColumn[] $columns
   * @param MySQLKey[]    $indexes
   */
  public function __construct(string $name, string $description = '', array $columns = [], array $indexes = [])
  {
    parent::__construct($name, $description);
    $this->_columns = Arrays::instancesOf($columns, CassandraColumn::class);
    $this->_keys = Arrays::instancesOf($indexes, CassandraKey::class);
  }

  public function getDatabase(): Database
  {
    return new CassandraKeyspace();
  }

  /**
   * @return CassandraColumn[]
   */
  public function getColumns(): array
  {
    return $this->_columns;
  }

  /**
   * @return CassandraKey[]
   */
  public function getKeys(): array
  {
    return $this->_keys;
  }
}
