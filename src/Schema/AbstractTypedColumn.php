<?php
namespace Packaged\DalSchema\Schema;

abstract class AbstractTypedColumn implements TypedSchemaColumn
{
  private $_type;
  private $_name;

  /**
   * @return string
   */
  public function getType(): string
  {
    return $this->_type;
  }

  /**
   * @param string $type
   *
   * @return $this
   */
  protected function _setType(string $type)
  {
    $this->_type = $type;
    return $this;
  }

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
