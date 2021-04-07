<?php

namespace Packaged\DalSchema;

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
