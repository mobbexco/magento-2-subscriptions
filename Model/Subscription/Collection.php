<?php

namespace Mobbex\Subscriptions\Model\Subscription;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Mobbex\Subscriptions\Model\Subscription', 'Mobbex\Subscriptions\Model\Subscription\Resource');
    }
}