<?php
namespace Packaged\DalSchema\Abstracts;

use Packaged\DalSchema\Database;
use Packaged\DalSchema\Table;

abstract class AbstractTable implements Table
{
  protected $_database;
  protected $_name;
  protected $_description;

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
}
