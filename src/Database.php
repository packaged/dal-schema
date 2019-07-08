<?php
namespace Packaged\DalSchema;

interface Database extends Writer
{
  public function getName(): string;
}
