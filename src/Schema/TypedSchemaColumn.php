<?php
namespace Packaged\DalSchema\Schema;

interface TypedSchemaColumn extends SchemaColumn
{
  public function getType(): string;
}
