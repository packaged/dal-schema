<?php
namespace Packaged\DalSchema\Schema;

interface Index
{
  public function getName(): string;

  public function getColumns(): array;
}
