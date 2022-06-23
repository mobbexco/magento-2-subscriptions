<?php

namespace Mobbex\Subscriptions\Helper;

class Sdk extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var \Mobbex\Subscriptions\Helper\Config */
    public $config;

    /** @var \Magento\Framework\Module\ResourceInterface */
    public $moduleResource;

    /** @var \Magento\Framework\App\ProductMetadataInterface */
    public $productMetadata;

    public function __construct(
        \Mobbex\Subscriptions\Helper\Config $config,
        \Magento\Framework\Module\ResourceInterface $moduleResource,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->config          = $config;
        $this->moduleResource  = $moduleResource;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Allow to use SDK classes.
     */
    public function init()
    {
        // Set platform information
        \Mobbex\Platform::init('magento_2_subscriptions', $this->moduleResource->getDbVersion('Mobbex_Subscriptions'), [
            'magento' => $this->productMetadata->getVersion(),
            'webpay'  => $this->moduleResource->getDbVersion('Mobbex_Webpay'),
            'sdk'     => \Composer\InstalledVersions::getVersion('mobbexco/php-plugins-sdk'),
        ], $this->config->getAll());

        // Init api conector
        \Mobbex\Api::init();
    }
}