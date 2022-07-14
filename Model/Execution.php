<?php

namespace Mobbex\Subscriptions\Model;

class Execution extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var array {
     *     @type string $uid              Primaty key. UID generated in Mobbex.
     *     @type string $subscription_uid UID of parent subscription.
     *     @type string $subscriber_uid   UID of parent subscriber.
     *     @type int    $status           Payment status.
     *     @type float  $total            Amount charged.
     *     @type string $date             Date of payment.
     *     @type string $data             Encoded webhook data.
     * }
     */
    protected $_data;

    protected function _construct()
    {
        $this->_init(\Mobbex\Subscriptions\Model\Execution\Resource::class);
    }
}