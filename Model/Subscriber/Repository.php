<?php

namespace Mobbex\Subscriptions\Model;

class SubscriberRepository
{
    /** @var \Psr\Log\LoggerInterface */
    public $logger;

    /** @var \Mobbex\Subscriptions\Model\SubscriberFactory */
    public $factory;

    /** @var \Mobbex\Subscriptions\Model\SubscriberResource */
    public $resource;

    /** @var \Mobbex\Subscriptions\Model\SubscriberCollectionFactory */
    public $collectionFactory;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionRepository */
    public $subscriptionRepository;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mobbex\Subscriptions\Model\SubscriberFactory $factory,
        \Mobbex\Subscriptions\Model\SubscriberResource $resource,
        \Mobbex\Subscriptions\Model\SubscriberCollectionFactory $collectionFactory,
        \Mobbex\Subscriptions\Model\SubscriptionFactory $subscriptionRepository
    ) {
        $this->logger                 = $logger;
        $this->factory                = $factory;
        $this->resource               = $resource;
        $this->collectionFactory      = $collectionFactory;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Get subscriber from cart id.
     * 
     * @param int|string $id
     * 
     * @return \Mobbex\Subscriptions\Model\Subscriber 
     */
    public function get($id)
    {
        $subscriber = $this->factory->create();
        $this->resource->load($subscriber, $id);
        return $subscriber;
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
        $this->resource->save($subscriber);
        return $subscriber;
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
        $subscription = $this->subscriptionRepository->getByUid($subscriber->getData('subscription_uid'));

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