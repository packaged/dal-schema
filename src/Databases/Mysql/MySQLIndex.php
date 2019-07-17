<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Index;

class MySQLIndex implements Index
{
  protected $_name;
  protected $_type;
  protected $_columns;

  public function __construct(string $name, MySQLKeyType $type, ...$columnNames)
  {
    $this->_name = $name;
    $this->_type = $type;
    $this->_columns = array_filter($columnNames, 'is_string');
  }

  public function getName(): string
  {
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

    $type = $this->getType()->toUpper();
    if($type === 'PRIMARY')
    {
      return 'PRIMARY KEY' . $cols;
    }

    return 'CONSTRAINT `' . $this->getName() . '` ' . $type . $cols;
  }

  public function writerAlter(): string
  {
    // TODO: Implement writerAlter() method.
  }
}
