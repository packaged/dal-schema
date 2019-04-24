<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Schema\AbstractDatabase;

class MySQLDatabase extends AbstractDatabase
{
  public function getCharacterSet(): ?MySQLCharacterSet
  {
    return null;
  }

  public function getCollation(): ?MySQLCollation
  {
    return null;
  }
}
