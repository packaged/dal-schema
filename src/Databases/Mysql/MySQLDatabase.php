<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\DalSchema\Abstracts\AbstractDatabase;

class MySQLDatabase extends AbstractDatabase
{
  protected $_characterSet;
  protected $_collation;
  protected $_ifNotExists = false;

  public function __construct(
    string $name, MySQLCollation $collation = null, MySQLCharacterSet $characterSet = null, bool $ifNotExists = false
  )
  {
    parent::__construct($name);
    $this->_collation = $collation;
    $this->_characterSet = $characterSet;
    $this->_ifNotExists = $ifNotExists;
  }

  public function getCharacterSet(): ?MySQLCharacterSet
  {
    return $this->_characterSet;
  }

  public function getCollation(): ?MySQLCollation
  {
    return $this->_collation;
  }

  public function ifNotExists(): bool
  {
    return $this->_ifNotExists;
  }

  public function writerCreate(): string
  {
    $charset = $this->getCharacterSet();
    $collation = $this->getCollation();
    $ifNotExists = $this->ifNotExists();
    return 'CREATE DATABASE'
      . ($ifNotExists ? ' IF NOT EXISTS' : '')
      . ' `' . $this->getName() . '`'
      . ($charset ? ' CHARACTER SET ' . $charset : '')
      . ($collation ? ' COLLATION ' . $collation : '');
  }

  public function writerAlter(): string
  {
    // TODO: Implement writerAlter() method.
    return '';
  }
}
