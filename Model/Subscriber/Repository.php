<?php

namespace Mobbex\Subscriptions\Model\Subscriber;

class Repository extends \Mobbex\Subscriptions\Model\Repository
{
    /** @var \Mobbex\Subscriptions\Model\SubscriberFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\Subscriber\Resource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\Subscriber\CollectionFactory */
    public $collectionFactory;

    /** @var \Mobbex\Subscriptions\Model\Subscription\Repository */
    public $subscriptionRepository;

    public function __construct(
        \Mobbex\Subscriptions\Model\SubscriberFactory $factory,
        \Mobbex\Subscriptions\Model\Subscriber\Resource $resource,
        \Mobbex\Subscriptions\Model\Subscriber\CollectionFactory $collectionFactory,
        \Mobbex\Subscriptions\Model\SubscriptionFactory $subscriptionRepository
    ) {
        $this->factory                = $factory;
        $this->resource               = $resource;
        $this->collectionFactory      = $collectionFactory;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Build a Subscriber from cart id.
     * 
     * @param array $props {
     *     @type int    $cart_id          Primaty key.
     *     @type string $subscription_uid UID of parent subscription.
     *     @type bool   $test             Test mode active.
     *     @type string $name             Customer name.
     *     @type string $email            Customer email.
     *     @type string $phone            Customer phone.
     *     @type string $identification   Customer identification (ex. DNI).
     *     @type int    $customer_id      Platform customer ID.
     * }
     * 
     * @return \Mobbex\Subscriptions\Model\Subscriber
     */
    public function build($props = [])
    {
        return parent::build(...func_get_args());
    }

    /**
     * Get subscriber from the value and field given.
     * 
     * @param mixed $value The value to match.
     * @param string $field The field name where search. Primary key by default.
     * 
     * @return \Mobbex\Subscriptions\Model\Subscriber
     */
    public function get($value = null, $field = null)
    {
        return parent::get(...func_get_args());
    }

    /**
     * Save subscriber to db.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscriber $subscriber
     * @param \Mobbex\Modules\Subscriber $module Send to fill mobbex response data before save.
     * 
     * @return \Mobbex\Subscriptions\Model\Subscriber
     */
    public function save($subscriber, $module = null)
    {
        if ($module)
            $this->fill($subscriber, [
                'uid'         => $module->uid        ?? $subscriber->uid,
                'source_url'  => $module->sourceUrl  ?? $subscriber->source_url,
                'control_url' => $module->controlUrl ?? $subscriber->control_url,
            ]);

        return parent::save($subscriber);
    }

    /**
     * Synchronize subscriber with Mobbex.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscriber $subscriber
     * 
     * @return \Mobbex\Modules\Subscriber Subscriber module.
     * 
     * @throws \Mobbex\Exception
     */
    public function sync($subscriber)
    {
        // Get subscription to calculate subscriber start date
        $subscription = $this->subscriptionRepository->get($subscriber->getData('subscription_uid'), 'uid');

        // Make API call using module
        $module = new \Mobbex\Modules\Subscriber(
            $subscriber->getData('cart_id'),
            $subscriber->getData('uid'),
            $subscriber->getData('subscription_uid'),
            $subscription->calculateDates()['current'],
            [
                'name'           => $subscriber->getData('name'),
                'email'          => $subscriber->getData('email'),
                'phone'          => $subscriber->getData('phone'),
                'identification' => $subscriber->getData('identification'),
                'uid'            => $subscriber->getData('customer_id'),
            ]
        );

        if (!$module->uid)
            throw new \Mobbex\Exception('[Mobbex] Error Synchronizing Subscriber: Empty UID on mobbex response');

        return $module;
    }
}