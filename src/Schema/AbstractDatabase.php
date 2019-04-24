<?php
namespace Packaged\DalSchema\Schema;

use Packaged\DalSchema\Databases\SchemaDatabase;

abstract class AbstractDatabase implements SchemaDatabase
{
  protected $_name;

  public function __construct(string $name = '')
  {
    $this->_name = $name;
  }

  public function getName(): string
  {
    return $this->_name;
  }

}
