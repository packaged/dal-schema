<?php
namespace Packaged\DalSchema;

interface Key extends Writer
{
  public function getName(): string;

  public function getColumns(): array;
}
