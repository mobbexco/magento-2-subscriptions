<?php

namespace Mobbex\Subscriptions\Model\Execution;

class Resource extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('mobbex_execution', 'uid');
    }
}