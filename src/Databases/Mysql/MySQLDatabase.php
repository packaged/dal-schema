<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Schema\AbstractDatabase;

class MySQLDatabase extends AbstractDatabase
{
  protected $_characterSet;
  protected $_collation;

  public function __construct(
    string $name = '', MySQLCharacterSet $characterSet = null, MySQLCollation $collation = null
  )
  {
    parent::__construct($name);
    $this->_characterSet = $characterSet;
    $this->_collation = $collation;
  }

  public function getCharacterSet(): ?MySQLCharacterSet
  {
    return $this->_characterSet;
  }

  public function getCollation(): ?MySQLCollation
  {
    return $this->_collation;
  }
}
