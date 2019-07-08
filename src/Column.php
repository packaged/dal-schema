<?php
namespace Packaged\DalSchema;

interface Column extends Writer
{
  public function getName(): string;
}
