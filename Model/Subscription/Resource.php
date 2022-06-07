<?php

namespace Mobbex\Subscriptions\Model;

class SubscriptionResource extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('mobbex_subscription', 'product_id');
    }
}