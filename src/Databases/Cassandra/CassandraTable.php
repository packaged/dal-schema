<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLEngine;
use Packaged\DalSchema\Databases\Mysql\MySQLIndex;
use Packaged\DalSchema\Databases\SchemaDatabase;
use Packaged\DalSchema\Schema\AbstractTable;
use Packaged\Helpers\Arrays;

class CassandraTable extends AbstractTable
{

  protected $_columns;
  protected $_indexes;

  /**
   * MySQLTable constructor.
   *
   * @param string           $name
   * @param string           $description
   * @param MySQLColumn[]    $columns
   * @param MySQLIndex[]     $indexes
   * @param MySQLEngine|null $engine
   */
  public function __construct(
    string $name, string $description = '', array $columns = [], array $indexes = [], MySQLEngine $engine = null
  )
  {
    parent::__construct($name, $description);
    $this->_columns = Arrays::instancesOf($columns, CassandraColumn::class);
    $this->_indexes = Arrays::instancesOf($indexes, CassandraIndex::class);
  }

  public function getDatabase(): SchemaDatabase
  {
    return new CassandraDatabase();
  }

  /**
   * @return CassandraColumn[]
   */
  public function getColumns(): array
  {
    return $this->_columns;
  }

  /**
   * @return CassandraIndex[]
   */
  public function getIndexes(): array
  {
    return $this->_indexes;
  }
}
