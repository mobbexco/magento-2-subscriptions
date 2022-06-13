<?php

namespace Mobbex\Subscriptions\Model;

class SubscriberRepository extends \Mobbex\Subscriptions\Model\Repository
{
    /** @var \Mobbex\Subscriptions\Model\SubscriberFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\SubscriberResource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\SubscriberCollectionFactory */
    public $collectionFactory;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionRepository */
    public $subscriptionRepository;

    public function __construct(
        \Mobbex\Subscriptions\Model\SubscriberFactory $factory,
        \Mobbex\Subscriptions\Model\SubscriberResource $resource,
        \Mobbex\Subscriptions\Model\SubscriberCollectionFactory $collectionFactory,
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
     * 
     * @return \Mobbex\Subscriptions\Model\Subscriber
     */
    public function save($subscriber)
    {
        return parent::save(...func_get_args());
    }

    /**
     * Synchronize subscriber with Mobbex.
     * 
     * @param \Mobbex\Subscriptions\Model\Subscriber $subscriber
     * 
     * @return \Mobbex\Modules\Subscriber Subscriber module.
     */
    public function sync($subscriber)
    {
        $subscription = $this->subscriptionRepository->get($subscriber->getData('subscription_uid'), 'uid');

        try {
            return new \Mobbex\Modules\Subscriber(
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
        } catch (\Exception $e) {
            $this->logger->error('[Mobbex] Error Synchronizing Subscriber: ' . $e->getMessage());
        }
    }
}