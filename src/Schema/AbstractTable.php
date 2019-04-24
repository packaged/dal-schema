<?php
namespace Packaged\DalSchema\Schema;

abstract class AbstractTable implements Table
{
  protected $_name;
  protected $_description;

  public function __construct(string $name, string $description = '')
  {
    $this->_name = $name;
    $this->_description = $description;
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
