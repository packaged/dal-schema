<?php
namespace Packaged\DalSchema\Schema;

use Packaged\DalSchema\Databases\SchemaDatabase;

interface SchemaTable
{
  public function getDatabase(): SchemaDatabase;

  public function getName(): string;

  public function getDescription(): ?string;

  /**
   * @return SchemaColumn[]
   */
  public function getColumns(): array;

  /**
   * @return SchemaIndex[]
   */
  public function getIndexes(): array;
}
