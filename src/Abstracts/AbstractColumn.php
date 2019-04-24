<?php
namespace Packaged\DalSchema\Abstracts;

use Packaged\DalSchema\Column;

abstract class AbstractColumn implements Column
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
