<?php

namespace Packaged\DalSchema;

use Packaged\Dal\Ql\IQLDataConnection;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
use Packaged\DalSchema\Databases\Mysql\MySQLTable;
use Packaged\Helpers\Path;
use ReflectionClass;

class DalSchema
{
  private static function _findFiles($path, $namespace)
  {
    $namespace = rtrim($namespace, '\\') . '\\';

    $files = [];
    $di = new \DirectoryIterator($path);
    foreach($di as $file)
    {
      $fn = $file->getFilename();
      if($file->isDir() && !$file->isDot())
      {
        $files = $files + static::_findFiles(Path::system($path, $fn), $namespace . $fn . '\\');
      }
      else if($file->getExtension() == 'php')
      {
        $files[$fn] = $namespace;
      }
    }
    return $files;
  }

  public static function migrateDatabases(IQLDataConnection $connection, Database ...$databases)
  {
    $getParser = static function (Database $database) use ($connection): ?Parser {
      static $parsers = [];
      if($database instanceof MySQLTable)
      {
        if(!isset($parsers['mysql']))
        {
          $parsers['mysql'] = new Parser\MySQL\MySQLParser($connection);
        }
        return $parsers['mysql'];
      }
      return null;
    };

    foreach($databases as $db)
    {
      if($db instanceof MySQLDatabase)
      {
        $parser = new Parser\MySQL\MySQLParser($connection);
      }
      else
      {
        throw new \Exception('unsupported database type');
      };

      // check db
      $currentDb = $parser->parseDatabase($db->getName());
      $dbQuery = $currentDb ? $db->writerAlter($currentDb) : $db->writerCreate();
      if($dbQuery)
      {
        $connection->runQuery($dbQuery);
      }
    }
  }

  public static function migrateTables(IQLDataConnection $connection, string $dbName, Table ...$tables)
  {
    $getParser = static function (Table $table) use ($connection): ?Parser {
      static $parsers = [];
      if($table instanceof MySQLTable)
      {
        if(!isset($parsers['mysql']))
        {
          $parsers['mysql'] = new Parser\MySQL\MySQLParser($connection);
        }
        return $parsers['mysql'];
      }
      return null;
    };

    //todo: check foreign key dependencies, reorder accordingly

    foreach($tables as $table)
    {
      $parser = $getParser($table);
      if($parser === null)
      {
        throw new \Exception('unsupported table type');
      }

      // check table
      $currentTable = $parser->parseTable($dbName, $table->getName());
      $dbQuery = $currentTable ? $table->writerAlter($currentTable) : $table->writerCreate();
      if($dbQuery)
      {
        $connection->runQuery($dbQuery);
      }
    }
  }

  /**
   * @param string $path
   * @param string $namespace
   *
   * @return Table[]
   * @throws \ReflectionException
   */
  public static function findSchemas(string $path, string $namespace): array
  {
    $tables = [];
    $schemaFiles = self::_findFiles($path, $namespace);
    foreach($schemaFiles as $file => $namespace)
    {
      $class = $namespace . substr(basename($file), 0, -4);
      $object = new ReflectionClass($class);

      if($object->isInstantiable() && $object->implementsInterface(DalSchemaProvider::class)
        && (!$object->getConstructor() || $object->getConstructor()->getNumberOfRequiredParameters() == 0))
      {
        $dao = $object->newInstance();
        if($dao instanceof DalSchemaProvider)
        {
          try
          {
            $tables[] = $dao->getDaoSchema();
          }
          catch(\Throwable $e)
          {
            // These exceptions need to be handled
            throw $e;
          }
        }
      }
    }
    return $tables;
  }
}
