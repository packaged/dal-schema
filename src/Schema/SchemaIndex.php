<?php
namespace Packaged\DalSchema\Schema;

interface SchemaIndex
{
  public function getName(): string;

  public function getColumns(): array;
}
