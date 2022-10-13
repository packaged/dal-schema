<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Exception;
use Packaged\DalSchema\Abstracts\AbstractTable;
use Packaged\DalSchema\Column;
use Packaged\DalSchema\Key;
use Packaged\DalSchema\Writer;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Objects;

class MySQLTable extends AbstractTable
{
  protected $_collation;
  protected $_charset;
  protected $_engine;

  /**
   * MySQLTable constructor.
   *
   * @param string $name
   * @param string $description
   */
  public function __construct(string $name, string $description = '')
  {
    parent::__construct($name, $description);
    $this->_engine = MySQLEngine::INNODB();
  }

  public function getCollation(): ?MySQLCollation
  {
    return $this->_collation;
  }

  public function setCollation(MySQLCollation $collation): self
  {
    $this->_collation = $collation;
    return $this;
  }

  public function getCharacterSet(): ?MySQLCharacterSet
  {
    return $this->_charset;
  }

  public function setCharacterSet(MySQLCharacterSet $charset): self
  {
    $this->_charset = $charset;
    return $this;
  }

  public function getEngine(): ?MySQLEngine
  {
    return $this->_engine;
  }

  public function setEngine(MySQLEngine $engine): self
  {
    $this->_engine = $engine;
    return $this;
  }

  /**
   * @return MySQLKey[]
   */
  public function getKeys(): array
  {
    return parent::getKeys();
  }

  public function addColumn(Column ...$column): self
  {
    return parent::addColumn(...Arrays::instancesOf($column, MySQLColumn::class));
  }

  public function addKey(Key ...$key): self
  {
    return parent::addKey(...Arrays::instancesOf($key, MySQLKey::class));
  }

  public function writerCreate(): string
  {
    $tableOpts = [];
    $engine = $this->getEngine();
    if($engine)
    {
      $tableOpts[] = 'ENGINE ' . $engine;
    }
    $charset = $this->getCharacterSet();
    if(!$charset && $this->_collation)
    {
      $charset = $this->_collation->getChatacterSet();
    }
    if($charset)
    {
      $tableOpts[] = 'CHARACTER SET ' . $charset;
    }
    $collation = $this->getCollation();
    if($collation)
    {
      $tableOpts[] = 'COLLATE ' . $collation;
    }

    return 'CREATE TABLE `' . $this->getName() . '`'
      . ' (' . implode(
        ', ',
        array_merge(
          Objects::mpull($this->getColumns(), 'writerCreate'),
          Objects::mpull($this->getKeys(), 'writerCreate')
        )
      ) . ') '
      . implode(' ', $tableOpts);
  }

  /**
   * @param Writer $old
   *
   * @return string
   * @throws Exception
   */
  public function writerAlter(Writer $old): string
  {
    if(!$old instanceof self)
    {
      throw new Exception('unexpected type provided to alter');
    }
    $parts = [];

    // name
    if($this->_name !== $old->getName())
    {
      $parts[] = 'RENAME `' . $old->getName() . '`';
    }

    // columns
    /** @var MySQLColumn[] $newCols */
    $newCols = Objects::mpull($this->getColumns(), null, 'getName');
    /** @var MySQLColumn[] $oldCols */
    $oldCols = Objects::mpull($old->getColumns(), null, 'getName');
    foreach($newCols as $col)
    {
      if(isset($oldCols[$col->getName()]))
      {
        $colChange = $col->writerAlter($oldCols[$col->getName()]);
        if($colChange)
        {
          $parts[] = $colChange;
        }
      }
      else
      {
        $parts[] = "ADD COLUMN " . $col->writerCreate();
      }
    }
    $removeCols = array_diff_key($oldCols, $newCols);
    foreach($removeCols as $col)
    {
      $parts[] = 'DROP COLUMN `' . $col->getName() . '`';
    }

    // keys
    /** @var MySQLKey[] $newKeys */
    $newKeys = Objects::mpull($this->getKeys(), null, 'getName');
    /** @var MySQLKey[] $oldKeys */
    $oldKeys = Objects::mpull($old->getKeys(), null, 'getName');
    foreach($newKeys as $key)
    {
      if(isset($oldKeys[$key->getName()]))
      {
        $colChange = $key->writerAlter($oldKeys[$key->getName()]);
        if($colChange)
        {
          $parts[] = $colChange;
        }
      }
      else
      {
        $parts[] = 'ADD ' . $key->writerCreate();
      }
    }

    // TODO: engine
    // TODO: charset
    // TODO: collation

    if($parts)
    {
      return 'ALTER TABLE `' . $this->getName() . '` ' . implode(', ', $parts);
    }
    return '';
  }
}
