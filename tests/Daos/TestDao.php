<?php

namespace Packaged\Tests\DalSchema\Daos;

use Packaged\DalSchema\DaoSchemaProvider;
use Packaged\DalSchema\Table;

class TestDao implements DaoSchemaProvider
{
  protected $_daoSchema;

  public function setDaoSchema(Table $table)
  {
    $this->_daoSchema = $table;
  }

  public function getDaoSchema(): Table
  {
    return $this->_daoSchema;
  }
}
