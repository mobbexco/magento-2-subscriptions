<?php

namespace Mobbex\Subscriptions\Model\Execution;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Mobbex\Subscriptions\Model\Execution', 'Mobbex\Subscriptions\Model\Execution\Resource');
    }
}