<?php

namespace Mobbex\Subscriptions\Model\Subscription;

class Resource extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('mobbex_subscription', 'product_id');
    }
}