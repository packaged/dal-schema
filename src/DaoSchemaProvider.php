<?php

namespace Packaged\DalSchema;

interface DaoSchemaProvider
{
  public function getDaoSchema(): Table;
}
