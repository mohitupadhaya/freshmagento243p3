<?php
namespace Custom\Graphql\Model;

use Magento\Framework\Model\AbstractModel;

class Employee extends AbstractModel
{
    protected function _construct()
    {


    $this->_init(\Custom\Graphql\Model\ResourceModel\Employee::class)
    }
}
