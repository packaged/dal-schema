<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Exception;
use Packaged\DalSchema\Key;
use Packaged\DalSchema\Writer;

class MySQLKey implements Key
{
  protected $_name;
  protected $_type;
  protected $_columns;

  public function __construct(string $name, MySQLKeyType $type, string ...$columnNames)
  {
    $this->_name = $name;
    $this->_type = $type;
    $this->_columns = $columnNames;
  }

  public static function index(string $name, string ...$columnNames): self
  {
    return new static($name, MySQLKeyType::INDEX(), ...$columnNames);
  }

  public static function primary(string $name, string ...$columnNames): self
  {
    return new static($name, MySQLKeyType::PRIMARY(), ...$columnNames);
  }

  public static function unique(string $name, string ...$columnNames): self
  {
    return new static($name, MySQLKeyType::UNIQUE(), ...$columnNames);
  }

  public static function fulltext(string $name, string ...$columnNames): self
  {
    return new static($name, MySQLKeyType::FULLTEXT(), ...$columnNames);
  }

  public function getName(): string
  {
    if($this->getType()->is(MySQLKeyType::PRIMARY()))
    {
      return $this->getType()->toUpper();
    }
    return $this->_name;
  }

  public function getColumns(): array
  {
    return $this->_columns;
  }

  public function getType(): MySQLKeyType
  {
    return $this->_type;
  }

  public function writerCreate(): string
  {
    $cols = [];
    foreach($this->getColumns() as $col)
    {
      $cols[] = '`' . $col . '`';
    }
    $cols = '(' . implode(',', $cols) . ')';

    $type = $this->getType();
    if($type->is(MySQLKeyType::PRIMARY()))
    {
      return $type->toUpper() . ' ' . $cols;
    }

    return $type->toUpper() . ' `' . $this->getName() . '` ' . $cols;
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

    $addedCols = array_diff($this->getColumns(), $old->getColumns());
    $removedCols = array_diff($old->getColumns(), $this->getColumns());

    if(empty($addedCols) && empty($removedCols))
    {
      return '';
    }

    $type = $this->getType();
    if($type->is(MySQLKeyType::PRIMARY()))
    {
      $drop = 'DROP ' . $type->toUpper();
    }
    else
    {
      $drop = 'DROP ' . $type->toUpper() . ' `' . $this->getName() . '`';
    }
    return $drop . ', ADD ' . $this->writerCreate();
  }
}
