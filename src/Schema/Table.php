<?php
namespace Packaged\DalSchema\Schema;

interface Table
{
  public function getDatabase(): Database;

  public function getName(): string;

  public function getDescription(): ?string;

  /**
   * @return Column[]
   */
  public function getColumns(): array;

  /**
   * @return Index[]
   */
  public function getIndexes(): array;
}
