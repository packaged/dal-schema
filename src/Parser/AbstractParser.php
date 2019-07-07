<?php

namespace Packaged\DalSchema\Parser;

use Packaged\Dal\Ql\IQLDataConnection;

abstract class AbstractParser implements Parser
{
  protected $_connection;

  public function __construct(IQLDataConnection $connection)
  {
    $this->_connection = $connection;
  }
}
