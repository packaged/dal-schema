<?php
namespace Packaged\DalSchema;

interface Database extends Writer
{
  public function getName(): string;

  public function writerCreate(bool $ifNotExists = false): string;
}
