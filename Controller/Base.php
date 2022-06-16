<?php

namespace Mobbex\Subscriptions\Controller;

class Base extends \Magento\Framework\App\Action\Action
{
    /** @var \Psr\Log\LoggerInterface */
    public $logger;

    /** @var \Mobbex\Subscriptions\Helper\Config */
    public $config;

    /** @var \Magento\Checkout\Model\Type\Onepage */
    public $checkout;

    /** @var \Magento\Framework\Controller\Result\JsonFactory */
    public $resultJsonFactory;

    /** @var \Mobbex\Subscriptions\Model\SubscriberRepository */
    public $subscriberRepository;

    /** @var \Mobbex\Subscriptions\Model\SubscriptionRepository */
    public $subscriptionRepository;

    /** Current module config values */
    public $settings;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Mobbex\Subscriptions\Helper\Config $config,
        \Magento\Checkout\Model\Type\Onepage $checkout,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mobbex\Subscriptions\Model\SubscriberRepository $subscriberRepository,
        \Mobbex\Subscriptions\Model\SubscriptionRepository $subscriptionRepository
    ) {
        $this->logger                 = $logger;
        $this->config                 = $config;
        $this->checkout               = $checkout;
        $this->resultJsonFactory      = $resultJsonFactory;
        $this->subscriberRepository   = $subscriberRepository;
        $this->subscriptionRepository = $subscriptionRepository;

        // Set current config values on a prop for easy access
        $this->settings = $config->getAll();
    }

    /**
     * Create a json response and log if there is an error.
     * 
     * @param bool $success True if the status is ok.
     * @param string $message Result message.
     * @param mixed $data All extra data.
     * 
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function createJsonResponse($success, $message, $data = [])
    {
        if (!$success)
            $this->logger->error($message, $data);

        return $this->resultJsonFactory->create()->setData(
            compact('success', 'message', 'data')
        );
    }
}