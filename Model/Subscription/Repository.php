<?php

namespace Mobbex\Subscriptions\Model;

class SubscriptionRepository
{
    /** @var \Psr\Log\LoggerInterface */
    public $logger;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionResource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionCollectionFactory */
    public $collectionFactory;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mobbex\Subscriptions\Model\SubscriptionFactory $factory,
        \Mobbex\Subscriptions\Model\SubscriptionResource $resource,
        \Mobbex\Subscriptions\Model\SubscriptionCollectionFactory $collectionFactory
    ) {
        $this->logger            = $logger;
        $this->factory           = $factory;
        $this->resource          = $resource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get subscription from the value and field given.
     * 
     * @param mixed $value
     * @param string $field The field to search for the value, primary key by default.
     * 
     * @return \Mobbex\Subscriptions\Model\Subscription 
     */
    public function get($value, $field = null)
    {
        $subscription = $this->factory->create();
        $this->resource->load($subscription, $value, $field);
        return $subscription;
    }

    /**
     * Save subscription to db.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscription $subscription
     * 
     * @return \Mobbex\Subscriptions\Model\Subscription
     */
    public function save($subscription)
    {
        $this->resource->save($subscription);
        return $subscription;
    }

    /**
     * Synchronize subscription with Mobbex.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscription $subscription
     * 
     * @return \Mobbex\Modules\Subscription Subscription module.
     */
    public function sync($subscription)
    {
        try {
            return new \Mobbex\Modules\Subscription(
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
        } catch (\Exception $e) {
            $this->logger->error('[Mobbex] Error Synchronizing Subscription: ' . $e->getMessage());
        }
    }
}