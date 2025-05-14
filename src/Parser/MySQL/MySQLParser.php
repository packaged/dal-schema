<?php

namespace Packaged\DalSchema\Parser\MySQL;

use Exception;
use Packaged\DalSchema\Databases\Mysql\MySQLCharacterSet;
use Packaged\DalSchema\Databases\Mysql\MySQLCollation;
use Packaged\DalSchema\Databases\Mysql\MySQLColumn;
use Packaged\DalSchema\Databases\Mysql\MySQLColumnType;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLEngine;
use Packaged\DalSchema\Databases\Mysql\MySQLKey;
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
  public function parseDatabase(string $name): ?MySQLDatabase
  {
    $results = $this->_connection->fetchQueryResults(
      'select DEFAULT_CHARACTER_SET_NAME,DEFAULT_COLLATION_NAME '
      . 'from information_schema.SCHEMATA where SCHEMA_NAME = ?',
      [$name]
    );
    if(empty($results))
    {
      return null;
    }

    return new MySQLDatabase(
      $name,
      new MySQLCollation($results[0]['DEFAULT_COLLATION_NAME']),
      new MySQLCharacterSet($results[0]['DEFAULT_CHARACTER_SET_NAME'])
    );
  }

  /**
   * @param string $databaseName
   * @param string $tableName
   *
   * @return MySQLTable
   * @throws Exception
   */
  public function parseTable(string $databaseName, string $tableName): ?MySQLTable
  {
    $database = $this->parseDatabase($databaseName);

    $schemaResults = $this->_connection->fetchQueryResults(
      'select T.TABLE_COMMENT,T.ENGINE,T.TABLE_COLLATION, CCSA.character_set_name
      from information_schema.TABLES T,
         information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA
      WHERE CCSA.collation_name = T.table_collation 
      AND T.TABLE_SCHEMA = ? AND T.TABLE_NAME = ?',
      [$databaseName, $tableName]
    );
    if(empty($schemaResults))
    {
      return null;
    }

    $table = new MySQLTable($tableName, $schemaResults[0]['TABLE_COMMENT']);
    $table->setEngine(new MySQLEngine($schemaResults[0]['ENGINE']));

    $columnResults = $this->_connection->fetchQueryResults(
      'select COLUMN_NAME,COLUMN_DEFAULT,IS_NULLABLE,DATA_TYPE,COLUMN_TYPE,CHARACTER_SET_NAME,COLLATION_NAME,EXTRA ' .
      'from information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
      [$databaseName, $tableName]
    );
    if(empty($columnResults))
    {
      throw new Exception('Problem reading columns');
    }
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
      $table->addColumn($column);
    }

    $keyResults = $this->_connection->fetchQueryResults(
      'select INDEX_NAME,COLUMN_NAME,NON_UNIQUE '
      . 'from information_schema.STATISTICS ' .
      'WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY INDEX_NAME, SEQ_IN_INDEX',
      [$databaseName, $tableName]
    );
    if(!empty($keyResults))
    {
      $keyGroup = Arrays::igroup($keyResults, 'INDEX_NAME');
      foreach($keyGroup as $keyName => $items)
      {
        if($keyName === 'PRIMARY')
        {
          $type = MySQLKeyType::PRIMARY();
        }
        else if(ValueAs::bool(Arrays::value(reset($items), 'NON_UNIQUE')))
        {
          $type = MySQLKeyType::INDEX();
        }
        else
        {
          $type = MySQLKeyType::UNIQUE();
        }
        // todo: fulltext & constraint
        $table->addKey(new MySQLKey($keyName, $type, ...Arrays::ipull($items, 'COLUMN_NAME')));
      }
    }

    $table->setCollation(new MySQLCollation($schemaResults[0]['TABLE_COLLATION']));
    if(array_key_exists('character_set_name', $schemaResults[0]))
    {
      $table->setCollation(new MySQLCollation($schemaResults[0]['character_set_name']));
    }
    else
    {
      $table->setCollation(new MySQLCollation($schemaResults[0]['CHARACTER_SET_NAME']));
    }

    return $table;
  }
}
