<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Schema\SchemaIndex;
use Packaged\Helpers\Arrays;

class MySQLIndex implements SchemaIndex
{
  protected $_name;
  protected $_type;
  protected $_columns;

  public function __construct(string $name, MySQLKeyType $type, ...$columnNames)
  {
    $this->_name = $name;
    $this->_type = $type;
    $this->_columns = Arrays::instancesOf($columnNames, 'string');
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
}
