<?php

namespace Mobbex\Subscriptions\Model;

class ExecutionResource extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('mobbex_execution', 'uid');
    }
}