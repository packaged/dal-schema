<?php

namespace Packaged\DalSchema;

interface DalSchemaProvider
{
  public function getDaoSchema(): Table;
}
