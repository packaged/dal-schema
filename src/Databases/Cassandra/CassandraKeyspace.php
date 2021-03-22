<?php
namespace Packaged\DalSchema\Databases\Cassandra;

use Packaged\DalSchema\Abstracts\AbstractDatabase;
use Packaged\DalSchema\Writer;

class CassandraKeyspace extends AbstractDatabase
{
  public function writerCreate(bool $ifNotExists = false): string
  {
    // TODO: Implement writerCreate() method.
  }

  public function writerAlter(Writer $old): string
  {
    // TODO: Implement writerAlter() method.
  }
}
