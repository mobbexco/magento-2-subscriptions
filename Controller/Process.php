<?php

namespace Mobbex\Subscriptions\Controller;

class Process extends \Mobbex\Subscriptions\Controller\Base
{
    public function execute()
    {
        // Get quote data
        $quote           = $this->checkout->getQuote();
        $shippingAddress = $quote->getBillingAddress()->getData();

        // Search subscriptions for current quote
        $subscriptions = $this->subscriptionRepository->getFromQuote($quote);
        $subscription  = reset($subscriptions);

        // Validate that there is only one subscription
        if (count($subscriptions) != 1)
            return $this->createJsonResponse(false, '[Mobbex] Error Obtaining Quote Subscriptions: None or multiple found');

        // Create subscriber
        $subscriber = $this->subscriberRepository->build([
            'cart_id'           => $quote->getId(),
            'subscription_uid ' => $subscription->uid,
            'test'              => $this->settings['test'],
            'name'              => "$shippingAddress[firstname] $shippingAddress[lastname]",
            'email'             => $quote->getCustomerEmail(),
            'phone'             => $shippingAddress['telephone'],
            'identification'    => null, // TODO: Get using custom fields
            'customer_id'       => $quote->getCustomerId(),
        ]);

        // Try to sync with mobbex and save to db
        try {
            $this->subscriberRepository->save($subscriber, $this->subscriberRepository->sync($subscriber));
        } catch (\Exception $e) {
            return $this->createJsonResponse(false, $e->getMessage(), (array) !empty($e->data) ? $e->data : null);
        }

        return $this->createJsonResponse(true, 'OK', [
            'id'         => $subscription->uid,
            'sid'        => $subscriber->uid,
            'url'        => $subscriber->source_url,
            'return_url' => $subscription->getEndpoint('callback'),
        ]);
    }
}