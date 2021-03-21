<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Exception;
use Packaged\DalSchema\Abstracts\AbstractDatabase;
use Packaged\DalSchema\Writer;

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

  /**
   * @param Writer $old
   *
   * @return string
   * @throws Exception
   */
  public function writerAlter(Writer $old): string
  {
    if(!$old instanceof static)
    {
      throw new Exception('unexpected type provided to alter');
    }
    $parts = [];
    if($this->_name !== $old->getName())
    {
      throw new Exception('Cannot rename databases in MySQL');
    }
    if($this->_characterSet && !$this->_characterSet->is($old->getCharacterSet()))
    {
      $parts[] = 'CHARACTER SET ' . $this->_characterSet;
    }
    if($this->_collation && !$this->_collation->is($old->getCollation()))
    {
      $parts[] = 'COLLATION ' . $this->_collation;
    }
    if($parts)
    {
      return 'ALTER DATABASE `' . $this->getName() . '` ' . implode(' ', $parts);
    }
    return '';
  }
}
