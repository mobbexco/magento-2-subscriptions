<?php

namespace Mobbex\Subscriptions\Model;

class SubscriptionRepository extends \Mobbex\Subscriptions\Model\Repository
{
    /** @var \Mobbex\Subscriptions\Model\SubscriptionFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionResource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionCollectionFactory */
    public $collectionFactory;

    public function __construct(
        \Mobbex\Subscriptions\Model\SubscriptionFactory $factory,
        \Mobbex\Subscriptions\Model\SubscriptionResource $resource,
        \Mobbex\Subscriptions\Model\SubscriptionCollectionFactory $collectionFactory
    ) {
        $this->factory           = $factory;
        $this->resource          = $resource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Build a Subscription from product id.
     * 
     * @param array $props {
     *     @type int    $product_id  Primary key.
     *     @type string $type        Execution type, can be "manual" or "dynamic".
     *     @type string $name        Name displayed to subscribers.
     *     @type string $description Description displayed to subscribers.
     *     @type float  $total       Amount to charge.
     *     @type float  $signup_fee  Different initial amount.
     *     @type string $interval    Interval between executions.
     *     @type int    $limit       Maximum number of executions.
     *     @type int    $free_trial  Number of free periods.
     * }
     * 
     * @return \Mobbex\Subscriptions\Model\Subscription
     */
    public function build($props = [])
    {
        return parent::build(...func_get_args());
    }

    /**
     * Get subscription from the value and field given.
     * 
     * @param mixed $value
     * @param string $field The field to search for the value, primary key by default.
     * 
     * @return \Mobbex\Subscriptions\Model\Subscription 
     */
    public function get($value = null, $field = null)
    {
        return parent::get(...func_get_args());
    }

    /**
     * Get all subscriptions from the items of quote given.
     * 
     * @param \Magento\Quote\Model\Quote $quote
     * 
     * @return \Mobbex\Subscriptions\Model\Subscription[]
     */
    public function getFromQuote($quote)
    {
        $subscriptions = [];

        foreach ($quote->getItemsCollection() as $item) {
            $subscription = $this->get($item->getProductId());

            // Add only if it was synced with mobbex
            if ($subscription->uid)
                $subscriptions[] = $subscription;
        }

        return $subscriptions;
    }

    /**
     * Save subscription to db.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscription $subscription
     * @param \Mobbex\Modules\Subscription $module Send to fill mobbex response data before save.
     * 
     * @return \Mobbex\Subscriptions\Model\Subscription
     */
    public function save($subscription, $module = null)
    {
        if ($module)
            $this->fill($subscription, [
                'uid' => $module->uid ?? $subscription->uid,
            ]);

        return parent::save($subscription);
    }

    /**
     * Synchronize subscription with Mobbex.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscription $subscription
     * 
     * @return \Mobbex\Modules\Subscription Subscription module.
     * 
     * @throws \Mobbex\Exception
     */
    public function sync($subscription)
    {
        $module = new \Mobbex\Modules\Subscription(
            $subscription->getData('product_id'),
            $subscription->getData('uid'),
            $subscription->getData('type'),
            $subscription->getEndpoint('callback'),
            $subscription->getEndpoint('webhook'),
            $subscription->getData('total'),
            $subscription->getData('name'),
            $subscription->getData('description'),
            $subscription->getData('interval'),
            $subscription->getData('limit'),
            $subscription->getData('free_trial'),
            $subscription->getData('signup_fee')
        );

        if (!$module->uid)
            throw new \Mobbex\Exception('[Mobbex] Error Synchronizing Subscription: Empty UID on mobbex response');

        return $module;
    }
}