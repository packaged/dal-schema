<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Database;
use Packaged\DalSchema\Abstracts\AbstractTable;
use Packaged\Helpers\Arrays;

class MySQLTable extends AbstractTable
{
  protected $_engine;
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
    $this->_engine = $engine ?: new MySQLEngine(MySQLEngine::INNODB);
    $this->_columns = Arrays::instancesOf($columns, MySQLColumn::class);
    $this->_indexes = Arrays::instancesOf($indexes, MySQLIndex::class);
  }

  public function getDatabase(): Database
  {
    return new MySQLDatabase();
  }

  public function getEngine(): ?MySQLEngine
  {
    return $this->_engine;
  }

  /**
   * @return MySQLColumn[]
   */
  public function getColumns(): array
  {
    return $this->_columns;
  }

  /**
   * @return MySQLIndex[]
   */
  public function getIndexes(): array
  {
    return $this->_indexes;
  }

}
