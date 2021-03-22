<?php
namespace Packaged\DalSchema;

interface Table extends Writer
{
  public function getDatabase(): Database;

  public function getName(): string;

  public function getDescription(): ?string;

  /**
   * @return Column[]
   */
  public function getColumns(): array;

  /**
   * @return Key[]
   */
  public function getIndexes(): array;
}
