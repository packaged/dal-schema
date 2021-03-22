<?php
namespace Packaged\DalSchema\Abstracts;

use Packaged\DalSchema\Column;
use Packaged\DalSchema\Database;
use Packaged\DalSchema\Index;
use Packaged\DalSchema\Table;

abstract class AbstractTable implements Table
{
  protected $_database;
  protected $_name;
  protected $_description;
  protected $_columns = [];
  protected $_indexes = [];

  public function __construct(Database $database, string $name, string $description = '')
  {
    $this->_database = $database;
    $this->_name = $name;
    $this->_description = $description;
  }

  public function getDatabase(): Database
  {
    return $this->_database;
  }

  public function getName(): string
  {
    return $this->_name;
  }

  public function getDescription(): ?string
  {
    return $this->_description;
  }

  /**
   * @return Column[]
   */
  public function getColumns(): array
  {
    return $this->_columns;
  }

  /**
   * @param Column ...$column
   *
   * @return $this
   */
  public function addColumn(Column ...$column): self
  {
    $this->_columns[] = $column;
    return $this;
  }

  /**
   * @return Index[]
   */
  public function getIndexes(): array
  {
    return $this->_indexes;
  }

  public function addIndex(Index ...$index): self
  {
    $this->_indexes[] = $index;
    return $this;
  }
}
