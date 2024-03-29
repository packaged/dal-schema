<?php
namespace Packaged\DalSchema\Abstracts;

use Packaged\DalSchema\Column;
use Packaged\DalSchema\Key;
use Packaged\DalSchema\Table;

abstract class AbstractTable implements Table
{
  protected $_name;
  protected $_description;
  protected $_columns = [];
  protected $_keys = [];

  public static function i(string $name, string $description = ''): self
  {
    return new static($name, $description = '');
  }

  public function __construct(string $name, string $description = '')
  {
    $this->_name = $name;
    $this->_description = $description;
  }

  public function getName(): string
  {
    return $this->_name;
  }

  public function getDescription(): ?string
  {
    return $this->_description;
  }

  /**
   * @return Column[]
   */
  public function getColumns(): array
  {
    return $this->_columns;
  }

  /**
   * @param Column ...$column
   *
   * @return $this
   */
  public function addColumn(Column ...$column): self
  {
    $this->_columns = array_merge($this->_columns, $column);
    return $this;
  }

  /**
   * @return Key[]
   */
  public function getKeys(): array
  {
    return $this->_keys;
  }

  /**
   * @param Key ...$key
   *
   * @return $this
   */
  public function addKey(Key ...$key): self
  {
    $this->_keys = array_merge($this->_keys, $key);
    return $this;
  }
}
