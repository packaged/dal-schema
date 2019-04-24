<?php
namespace Packaged\DalSchema;

use Packaged\DalSchema\Databases\SchemaDatabase;

interface Schema
{
  public function getEngine(): SchemaDatabase;

  /**
   * @return string Database Name
   */
  public function getName(): string;
}
