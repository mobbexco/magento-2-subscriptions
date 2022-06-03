<?php

namespace Mobbex\Subscriptions\Model;

class SubscriberResource extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('mobbex_subscriber', 'cart_id');
    }
}