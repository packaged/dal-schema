<?php
namespace Packaged\DalSchema;

use Packaged\DalSchema\Engines\SchemaEngine;

interface Schema
{
  public function getEngine(): SchemaEngine;

  /**
   * @return string Database Name
   */
  public function getName(): string;
}
