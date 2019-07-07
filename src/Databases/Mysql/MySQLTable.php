<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Abstracts\AbstractTable;
use Packaged\DalSchema\Database;
use Packaged\Helpers\Arrays;

class MySQLTable extends AbstractTable
{
  protected $_collation;
  protected $_engine;
  protected $_columns;
  protected $_indexes;

  /**
   * MySQLTable constructor.
   *
   * @param Database         $database
   * @param string           $name
   * @param string           $description
   * @param MySQLColumn[]    $columns
   * @param MySQLIndex[]     $indexes
   * @param string|null      $collation
   * @param MySQLEngine|null $engine
   */
  public function __construct(
    Database $database,
    string $name, string $description = '', array $columns = [], array $indexes = [], string $collation = null,
    MySQLEngine $engine = null
  )
  {
    parent::__construct($database, $name, $description);
    $this->_collation = $collation;
    $this->_engine = $engine ?: new MySQLEngine(MySQLEngine::INNODB);
    $this->_columns = Arrays::instancesOf($columns, MySQLColumn::class);
    $this->_indexes = Arrays::instancesOf($indexes, MySQLIndex::class);
  }

  public function getCollation(): ?string
  {
    return $this->_collation;
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
