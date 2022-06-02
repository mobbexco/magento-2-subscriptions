<?php

namespace Mobbex\Subscriptions\Model\Config;

class Version extends \Magento\Framework\App\Config\Value
{
    /** @var \Magento\Framework\Module\ResourceInterface */
    public $moduleResource;

    public function __construct(
        \Magento\Framework\Module\ResourceInterface $moduleResource
    ) {
        $this->moduleResource = $moduleResource;
    }

    /**
     * Display module version.
     */
    public function afterLoad()
    {
        $version = $this->moduleResource->getDbVersion('Mobbex_Subscriptions');
        $this->setValue($version);
    }
}