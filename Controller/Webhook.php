<?php

namespace Mobbex\Subscriptions\Controller;

class Webhook extends \Mobbex\Subscriptions\Controller\Base
{
    /** @var \Magento\Quote\Model\QuoteFactory */
    public $quoteFactory;

    /** @var \Magento\Quote\Model\OrderFactory */
    public $orderFactory;

    public function __construct(
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->orderFactory = $orderFactory;
    }

    public function execute()
    {
        $post = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json' ? json_decode(file_get_contents('php://input'), true) : $this->_request->getPostValue();

        if (empty($post['data']) || empty($post['type']))
            return $this->createJsonResponse(false, '[Mobbex] Error Processing Webhook: Empty data or type nodes');

        // Get subscription and subscriber from uid
        $subscription = $this->subscriptionRepository->get($post['data']['subscription']['uid'], 'uid');
        $subscriber   = $this->subscriberRepository->get($post['data']['subscriber']['uid'], 'uid');

        // Get order for this subscriber
        $quote = $this->quoteFactory->create()->load($subscriber->getData('cart_id'));
        $order = $this->orderFactory->create()->load($quote->getReservedOrderId());

        if (!$subscription || !$subscriber || !$order)
            return $this->createJsonResponse(false, '[Mobbex] Error Processing Webhook: Order, subscription or subscriber cannot be loaded');

        switch ($_POST['type']) {
            case 'subscription:registration':
                // Save registration data and update subscriber state
                $subscriber->setData('state', $post['data']['context']['status'] == 'success');
                $subscriber->setData('register_data', json_encode($_POST['data']));
                $this->subscriberRepository->save($subscriber);

                // Continue only if validation was approved
                if (!$subscriber->getData('state'))
                    break;

                // Update order status
                $orderStatus = $this->getOrderStatus($post['data']['payment']['status']['code']);
                $order->setState($orderStatus)->setStatus($orderStatus);

                break;
            case 'subscription:execution':
                // Calculate execution dates
                $dates = $subscription->calculateDates();

                // Save execution to db
                $this->executionRepository->save($this->executionRepository->build([
                    'uid'              => $post['data']['execution']['uid'],
                    'subscription_uid' => $post['data']['subscription']['uid'],
                    'subscriber_uid'   => $post['data']['subscriber']['uid'],
                    'status'           => $post['data']['payment']['status']['code'],
                    'total'            => $post['data']['payment']['total'],
                    'date'             => $dates['current'],
                    'data'             => $post['data'],
                ]));

                // Update subscriber dates
                $subscriber->setData('last_execution', $dates['current']);
                $subscriber->setData('next_execution', $dates['next']);

                if (!$subscriber->getData('start_date') || strtotime($subscriber->getData('start_date')) < 0)
                    $subscriber->setData('start_date', $subscriber->getData('last_execution'));

                $this->subscriberRepository->save($subscriber);
                break;
        }

        return $this->createJsonResponse(true, 'OK');
    }

    /**
     * Get order status id from transaction status code.
     * 
     * @param int $code Mobbex transaction status code.
     * 
     * @return string
     */
    public function getOrderStatus($code)
    {
        // TODO: Add own active/inactive status to replace processing/canceled
        if ($code == 2 || $code == 3 || $code == 100 || $code == 201)
            return 'pending';
        else if ($code == 4 || $code >= 200 && $code < 400)
            return 'processing';
        else
            return 'canceled';
    }
}