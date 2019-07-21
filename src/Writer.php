<?php

namespace Packaged\DalSchema;

interface Writer
{
  public function writerCreate(): string;

  public function writerAlter(Writer $writer): string;
}
