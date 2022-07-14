<?php

namespace Mobbex\Subscriptions\Model\Subscriber;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Mobbex\Subscriptions\Model\Subscriber', 'Mobbex\Subscriptions\Model\Subscriber\Resource');
    }
}