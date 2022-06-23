<?php

namespace Mobbex\Subscriptions\Model;

class Gateway extends \Magento\Payment\Model\Method\AbstractMethod
{
    const CODE = 'mobbex_subscriptions';

    /** @var string */
    protected $_code = self::CODE;

    /** @var bool */
    protected $_isOffline = false;

    /** @var bool */
    protected $_isInitializeNeeded = true;

    /** @var bool */
    protected $_isGateway = true;

    /** @var bool */
    protected $_canAuthorize = false;

    /** @var bool */
    protected $_canCapture = false;

    /** @var bool */
    protected $_canRefund = false;

    /** @var bool */
    protected $_canRefundInvoicePartial = false;

    /** @var string */
    protected $_infoBlockType = 'Mobbex\Subscriptions\Block\Info';

    /** @var array */
    protected $_supportedCurrencyCodes = ['ARS'];

    /**
     * Executed after any payment action.
     * 
     * @param string $paymentAction
     * @param object $stateObject
     */
    public function initialize($paymentAction, $stateObject)
    {
        // Send mails only in success payments
        $this->getInfoInstance()->getOrder()->setCanSendNewEmailFlag(false);

        // Set default payment status
        $stateObject->setState(\Magento\Sales\Model\Order::STATE_NEW);
        $stateObject->setStatus(\Magento\Sales\Model\Order::STATE_NEW);

        // Mark customer as not notified
        $stateObject->setIsNotified(false);
    }

    /**
     * Return true if method and quote are active.
     * 
     * @param \Magento\Quote\Model\Quote $quote
     * 
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        return $this->isActive($quote ? $quote->getStoreId() : null);
    }
}