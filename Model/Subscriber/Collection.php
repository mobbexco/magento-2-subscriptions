<?php

namespace Mobbex\Subscriptions\Model;

class SubscriberCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Mobbex\Subscriptions\Model\Subscriber', 'Mobbex\Subscriptions\Model\SubscriberResource');
    }
}