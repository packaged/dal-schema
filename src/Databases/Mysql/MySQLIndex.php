<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Exception;
use Packaged\DalSchema\Index;
use Packaged\DalSchema\Writer;

class MySQLIndex implements Index
{
  protected $_name;
  protected $_type;
  protected $_columns;

  public function __construct(string $name, MySQLKeyType $type, string ...$columnNames)
  {
    $this->_name = $name;
    $this->_type = $type;
    $this->_columns = $columnNames;
  }

  public function getName(): string
  {
    if($this->getType()->is(MySQLKeyType::PRIMARY()))
    {
      return $this->getType()->toUpper();
    }
    return $this->_name;
  }

  public function getColumns(): array
  {
    return $this->_columns;
  }

  public function getType(): MySQLKeyType
  {
    return $this->_type;
  }

  public function writerCreate(): string
  {
    $cols = [];
    foreach($this->getColumns() as $col)
    {
      $cols[] = '`' . $col . '`';
    }
    $cols = ' (' . implode(',', $cols) . ')';

    $type = $this->getType();
    if($type->is(MySQLKeyType::PRIMARY()))
    {
      return 'PRIMARY KEY' . $cols;
    }

    return 'CONSTRAINT `' . $this->getName() . '` ' . $type->toUpper() . $cols;
  }

  /**
   * @param Writer $old
   *
   * @return string
   * @throws Exception
   */
  public function writerAlter(Writer $old): string
  {
    if(!$old instanceof static)
    {
      throw new Exception('unexpected type provided to alter');
    }

    $addedCols = array_diff($this->getColumns(), $old->getColumns());
    $removedCols = array_diff($old->getColumns(), $this->getColumns());

    if(empty($addedCols) && empty($removedCols))
    {
      return '';
    }
    return $this->writerCreate();
  }
}
