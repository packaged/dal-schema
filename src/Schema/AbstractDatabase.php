<?php
namespace Packaged\DalSchema\Schema;

abstract class AbstractDatabase implements Database
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
