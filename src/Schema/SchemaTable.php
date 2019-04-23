<?php
namespace Packaged\DalSchema\Schema;

use Packaged\DalSchema\Engines\SchemaEngine;

interface SchemaTable
{
  public function getEngine(): SchemaEngine;

  public function getName(): string;

  public function getComment(): ?string;

  /**
   * @return SchemaColumn[]
   */
  public function getColumns(): array;

  /**
   * @return SchemaKey[]
   */
  public function getKeys(): array;

  /**
   * @return SchemaIndex[]
   */
  public function getIndices(): array;
}
