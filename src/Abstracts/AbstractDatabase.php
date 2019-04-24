<?php
namespace Packaged\DalSchema\Abstracts;

use Packaged\DalSchema\Database;

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
