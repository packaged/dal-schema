<?php

namespace Packaged\Tests\DalSchema\Daos;

use Packaged\DalSchema\DalSchemaProvider;
use Packaged\DalSchema\Table;

class TestDao implements DalSchemaProvider
{
  protected Table $_daoSchema;

  public function setDaoSchema(Table $table)
  {
    $this->_daoSchema = $table;
  }

  public function getDaoSchema(): Table
  {
    return $this->_daoSchema;
  }
}
