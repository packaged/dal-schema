<?php

namespace Packaged\DalSchema\Parser;

use Packaged\DalSchema\Database;
use Packaged\DalSchema\Table;

interface Parser
{
  /**
   * @param string $name
   *
   * @return Database
   */
  public function parseDatabase(string $name);

  /**
   * @param string $database
   * @param string $tableName
   *
   * @return Table
   */
  public function parseTable(string $database, string $tableName);
}
