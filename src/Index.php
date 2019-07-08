<?php
namespace Packaged\DalSchema;

interface Index extends Writer
{
  public function getName(): string;

  public function getColumns(): array;
}
