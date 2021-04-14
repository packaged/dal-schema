<?php

namespace Packaged\DalSchema;

use Packaged\Dal\Ql\IQLDataConnection;
use Packaged\DalSchema\Databases\Cassandra\CassandraKeyspace;
use Packaged\DalSchema\Databases\Mysql\MySQLDatabase;
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

  public static function migrate(IQLDataConnection $connection, Table ...$tables)
  {
    $getParser = static function (Database $db) use ($connection) {
      static $parsers = [];
      if($db instanceof MySQLDatabase)
      {
        if(!isset($parsers['mysql']))
        {
          $parsers['mysql'] = new Parser\MySQL\MySQLParser($connection);
        }
        return $parsers['mysql'];
      }
      else if($db instanceof CassandraKeyspace)
      {
        if(!isset($parsers['cassandra']))
        {
          $parsers['cassandra'] = new Parser\MySQL\MySQLParser($connection);
        }
        return $parsers['cassandra'];
      }
      throw new \Exception('unsupported database type');
    };

    //todo: check foreign key dependencies, reorder accordingly
    foreach($tables as $table)
    {
      // check db
      $db = $table->getDatabase();
      $parser = $getParser($db);
      $currentDb = $parser->parseDatabase($db->getName());
      $dbQuery = $currentDb ? $db->writerAlter($currentDb) : $db->writerCreate();
      if($dbQuery)
      {
        $connection->runQuery($dbQuery);
      }

      // check table
      $currentTable = $parser->parseTable($db->getName(), $table->getName());
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
          }
        }
      }
    }
    return $tables;
  }
}
