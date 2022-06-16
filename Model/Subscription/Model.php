<?php

namespace Mobbex\Subscriptions\Model;

class Subscription extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var array {
     *     @type int    $product_id  Primary key.
     *     @type string $uid         UID generated in Mobbex.
     *     @type string $type        Execution type, can be "manual" or "dynamic".
     *     @type bool   $state       Current state.
     *     @type string $name        Name displayed to subscribers.
     *     @type string $description Description displayed to subscribers.
     *     @type float  $total       Amount to charge.
     *     @type float  $signup_fee  Different initial amount.
     *     @type string $interval    Interval between executions.
     *     @type int    $limit       Maximum number of executions.
     *     @type int    $free_trial  Number of free periods.
     * }
     */
    protected $_data;

    /** @var \Magento\Framework\UrlInterface */
    public $url;

    /** Possible interval periods */
    public $periods = [
        'd' => 'day',
        'm' => 'month',
        'y' => 'year',
    ];

    protected function _construct(
        \Magento\Framework\UrlInterface $url
    ) {
        $this->url = $url;
        $this->_init(\Mobbex\Subscriptions\Model\SubscriptionResource::class);
    }

    /**
     * Retrieve formatted current and next execution dates.
     * 
     * @return string[]
     */
    public function calculateDates()
    {
        $interval = preg_replace('/[^0-9]/', '', (string) $this->getData('interval')) ?: 1;
        $period   = $this->periods[preg_replace('/[0-9]/', '', (string) $this->getData('interval')) ?: 'm'];

        return [
            'current' => date('Y-m-d H:i:s'),
            'next'    => date('Y-m-d H:i:s', strtotime("+ $interval $period"))
        ];
    }

    /**
     * Create an endpoint url using the id of the subscription.
     * 
     * @param string $route Route to module controller file.
     * @param array $params List of params to pass as query.
     * 
     * @return string
     */
    public function getEndpoint($route, $params = [])
    {
        return $this->url->getUrl("subscriptions/$route", [
            '_secure'      => true,
            '_current'     => true,
            '_use_rewrite' => true,
            '_query'       => $params + [
                'product_id' => $this->getData('product_id'),
            ],
        ]);
    }
}