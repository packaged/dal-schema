<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Exception;
use Packaged\DalSchema\Abstracts\AbstractTable;
use Packaged\DalSchema\Database;
use Packaged\DalSchema\Writer;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Objects;

class MySQLTable extends AbstractTable
{
  protected $_collation;
  protected $_charset;
  protected $_engine;
  protected $_columns;
  protected $_indexes;

  /**
   * MySQLTable constructor.
   *
   * @param Database          $database
   * @param string            $name
   * @param string            $description
   * @param MySQLColumn[]     $columns
   * @param MySQLIndex[]      $indexes
   * @param MySQLCollation    $collation
   * @param MySQLCharacterSet $charset
   * @param MySQLEngine|null  $engine
   */
  public function __construct(
    Database $database,
    string $name, string $description = '', array $columns = [], array $indexes = [],
    MySQLCollation $collation = null, MySQLCharacterSet $charset = null,
    MySQLEngine $engine = null
  )
  {
    parent::__construct($database, $name, $description);
    $this->_collation = $collation;
    $this->_charset = $charset;
    $this->_engine = $engine ?: MySQLEngine::INNODB();
    $this->_columns = Arrays::instancesOf($columns, MySQLColumn::class);
    $this->_indexes = Arrays::instancesOf($indexes, MySQLIndex::class);
  }

  public function getCollation(): ?MySQLCollation
  {
    return $this->_collation;
  }

  public function getCharacterSet(): ?MySQLCharacterSet
  {
    return $this->_charset;
  }

  public function getEngine(): ?MySQLEngine
  {
    return $this->_engine;
  }

  /**
   * @return MySQLColumn[]
   */
  public function getColumns(): array
  {
    return $this->_columns;
  }

  /**
   * @return MySQLIndex[]
   */
  public function getIndexes(): array
  {
    return $this->_indexes;
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

    return 'CREATE TABLE `' . $this->getDatabase()->getName() . '`.`' . $this->getName()
      . '` (' . implode(
        ', ',
        array_merge(
          Objects::mpull($this->getColumns(), 'writerCreate'),
          Objects::mpull($this->getIndexes(), 'writerCreate')
        )
      ) . ') '
      . implode(' ', $tableOpts);
  }

  /**
   * @param Writer $w
   *
   * @return string
   * @throws Exception
   */
  public function writerAlter(Writer $w): string
  {
    if(!$w instanceof static)
    {
      throw new Exception('unexpected type provided to alter');
    }
    $parts = [];

    // name
    if($this->_name !== $w->getName())
    {
      $parts[] = 'RENAME `' . $w->getName() . '`';
    }

    // columns
    /** @var MySQLColumn[] $newCols */
    $newCols = Objects::mpull($this->getColumns(), null, 'getName');
    /** @var MySQLColumn[] $oldCols */
    $oldCols = Objects::mpull($w->getColumns(), null, 'getName');
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
        $parts[] = $col->writerCreate();
      }
    }
    $removeCols = array_diff_key($oldCols, $newCols);
    foreach($removeCols as $col)
    {
      $parts[] = 'DROP `' . $col->getName() . '`';
    }
    // TODO: indexes
    // TODO: engine
    // TODO: charset
    // TODO: collation

    if($parts)
    {
      return 'ALTER TABLE `' . $this->_name . '` ' . implode(' ', $parts);
    }
    return '';
  }
}
