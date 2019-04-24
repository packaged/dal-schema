<?php
namespace Packaged\DalSchema;

interface Index
{
  public function getName(): string;

  public function getColumns(): array;
}
