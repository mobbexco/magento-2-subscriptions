<?php

namespace Mobbex\Subscriptions\Model;

class SubscriptionCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Mobbex\Subscriptions\Model\Subscription', 'Mobbex\Subscriptions\Model\SubscriptionResource');
    }
}