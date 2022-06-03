<?php

namespace Mobbex\Subscriptions\Model;

class Subscriber extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var array {
     *     @type int    $cart_id          Primaty key.
     *     @type string $uid              UID generated in Mobbex.
     *     @type string $subscription_uid UID of parent subscription.
     *     @type bool   $state            Current state.
     *     @type bool   $test             Test mode active.
     *     @type string $name             Customer name.
     *     @type string $email            Customer email.
     *     @type string $phone            Customer phone.
     *     @type string $identification   Customer identification (ex. DNI).
     *     @type int    $customer_id      Platform customer ID.
     *     @type string $source_url       URL for change payment method.
     *     @type string $control_url      URL for manage subscription.
     *     @type string $register_data    Array with registration data.
     *     @type string $start_date       Date of first period.
     *     @type string $last_execution   Date of last execution.
     *     @type string $next_execution   Date for next execution.
     * }
     */
    protected $_data;

    protected function _construct()
    {
        $this->_init(\Mobbex\Subscriptions\Model\SubscriberResource::class);
    }
}