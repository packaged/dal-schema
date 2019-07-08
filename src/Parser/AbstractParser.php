<?php

namespace Packaged\DalSchema\Parser;

use Packaged\Dal\Ql\IQLDataConnection;
use Packaged\DalSchema\Parser;

abstract class AbstractParser implements Parser
{
  protected $_connection;

  public function __construct(IQLDataConnection $connection)
  {
    $this->_connection = $connection;
  }
}
