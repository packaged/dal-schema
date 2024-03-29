<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Key;
use Packaged\DalSchema\Writer;
use Packaged\Helpers\Arrays;

class CassandraKey implements Key
{
  protected $_name;
  protected $_type;
  protected $_columns;

  public function __construct(string $name, CassandraKeyType $type, ...$columnNames)
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

  public function getType(): CassandraKeyType
  {
    return $this->_type;
  }

  public function writerCreate(): string
  {
    // TODO: Implement writerCreate() method.
  }

  public function writerAlter(Writer $old): string
  {
    // TODO: Implement writerAlter() method.
  }
}
