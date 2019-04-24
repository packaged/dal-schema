<?php
namespace Packaged\DalSchema\Schema;

abstract class AbstractColumn implements SchemaColumn
{
  private $_name;

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->_name;
  }

  /**
   * @param string $name
   *
   * @return $this
   */
  protected function _setName(string $name)
  {
    $this->_name = $name;
    return $this;
  }

}
