<?php

namespace Packaged\DalSchema\Parser\MySQL;

use Exception;
use Packaged\DalSchema\Databases\Mysql\MySQLCharacterSet;
use Packaged\DalSchema\Databases\Mysql\MySQLCollation;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLEngine;
use Packaged\DalSchema\Databases\Mysql\MySQLIndex;
use Packaged\DalSchema\Databases\Mysql\MySQLKeyType;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\DalSchema\Parser\AbstractParser;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\ValueAs;

/**
 * Returns a MySQLTable
 */
class MySQLParser extends AbstractParser
{
  /**
   * @param string $name
   *
   * @return MySQLDatabase
   * @throws Exception
   */
  public function parseDatabase(string $name)
  {
    $results = $this->_connection->fetchQueryResults(
      'select DEFAULT_CHARACTER_SET_NAME,DEFAULT_COLLATION_NAME '
      . 'from information_schema.SCHEMATA where SCHEMA_NAME = ?',
      [$name]
    );
    if(empty($results))
    {
      throw new Exception('Database not found');
    }
    return new MySQLDatabase(
      $name,
      new MySQLCharacterSet($results[0]['DEFAULT_CHARACTER_SET_NAME']),
      new MySQLCollation($results[0]['DEFAULT_COLLATION_NAME'])
    );
  }

  /**
   * @param string $databaseName
   * @param string $tableName
   *
   * @return MySQLTable
   * @throws Exception
   */
  public function parseTable(string $databaseName, string $tableName)
  {
    $database = $this->parseDatabase($databaseName);

    $schemaResults = $this->_connection->fetchQueryResults(
      'select TABLE_COMMENT,ENGINE,TABLE_COLLATION '
      . 'from information_schema.TABLES '
      . 'WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
      [$databaseName, $tableName]
    );
    if(empty($schemaResults))
    {
      throw new Exception('Table not found');
    }

    $columnResults = $this->_connection->fetchQueryResults(
      'select COLUMN_NAME,COLUMN_DEFAULT,IS_NULLABLE,DATA_TYPE,COLUMN_TYPE,CHARACTER_SET_NAME,COLLATION_NAME,EXTRA ' .
      'from information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
      [$databaseName, $tableName]
    );
    if(empty($columnResults))
    {
      throw new Exception('Problem reading columns');
    }
    $columns = [];
    foreach($columnResults as $columnResult)
    {
      if(!preg_match('/^(.+?)(?:\(([0-9,]+)\))?(?: (unsigned))?$/', $columnResult['COLUMN_TYPE'], $colMatches))
      {
        throw new Exception('Could not parse column type: ' . $columnResult['COLUMN_TYPE']);
      }
      $column = new MySQLColumn(
        $columnResult['COLUMN_NAME'],
        new MySQLColumnType($colMatches[1] . ($colMatches[3] ?? '')),
        $colMatches[2] ?? null,
        ValueAs::bool($columnResult['IS_NULLABLE']),
        $columnResult['COLUMN_DEFAULT'],
        $columnResult['EXTRA'],
        $columnResult['CHARACTER_SET_NAME'] ? new MySQLCharacterSet($columnResult['CHARACTER_SET_NAME']) : null,
        $columnResult['COLLATION_NAME'] ? new MySQLCollation($columnResult['COLLATION_NAME']) : null
      );
      $columns[] = $column;
    }

    $indexResults = $this->_connection->fetchQueryResults(
      'select INDEX_NAME,COLUMN_NAME,NON_UNIQUE '
      . 'from information_schema.STATISTICS ' .
      'WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY INDEX_NAME, SEQ_IN_INDEX',
      [$databaseName, $tableName]
    );
    $indexes = [];
    if(!empty($indexResults))
    {
      $constraintGroup = Arrays::igroup($indexResults, 'INDEX_NAME');
      foreach($constraintGroup as $keyName => $items)
      {
        if($keyName === MySQLKeyType::PRIMARY()->toUpper())
        {
          $type = MySQLKeyType::PRIMARY();
        }
        else if(ValueAs::bool(Arrays::value($items[0], 'NON_UNIQUE')))
        {
          $type = MySQLKeyType::INDEX();
        }
        else
        {
          $type = MySQLKeyType::UNIQUE();
        }
        // todo: fulltext & constraint
        $indexes[] = new MySQLIndex($keyName, $type, ...Arrays::ipull($items, 'COLUMN_NAME'));
      }
    }

    $tableCollation = new MySQLCollation($schemaResults[0]['TABLE_COLLATION']);

    return new MySQLTable(
      $database,
      $tableName,
      $schemaResults[0]['TABLE_COMMENT'],
      $columns,
      $indexes,
      $tableCollation,
      new MySQLEngine($schemaResults[0]['ENGINE'])
    );
  }
}
