<?php

namespace Packaged\DalSchema;

interface Parser
{
  /**
   * @param string $name
   *
   * @return Database
   */
  public function parseDatabase(string $name);

  /**
   * @param string $databaseName
   * @param string $tableName
   *
   * @return Table
   */
  public function parseTable(string $databaseName, string $tableName);
}
