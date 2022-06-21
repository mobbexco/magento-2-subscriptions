<?php

namespace Mobbex\Subscriptions\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** Module configuration paths */
    public $configurationPaths = [
        'api_key'             => 'payment/webpay/api_key',
        'access_token'        => 'payment/webpay/access_token',
        'test'                => 'payment/webpay/test_mode',
        'embed'               => 'payment/webpay/embed',
        'theme'               => 'payment/webpay/appearance/theme',
        'color'               => 'payment/webpay/appearance/primary_color',
        'background'          => 'payment/webpay/appearance/background_color',
    ];

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        parent::__construct($context);
        $this->configWriter = $configWriter;
    }

    /**
     * Save a config value to db.
     * 
     * @param string $path Config identifier.
     * @param mixed $value Value to set.
     */
    public function save($path, $value)
    {
        $this->configWriter->save($path, $value);
    }

    /**
     * Get a config value from db.
     * 
     * @param string $path Config identifier. @see $this::$configurationPaths
     * @param string $store Store code.
     * 
     * @return mixed
     */
    public function get($name, $store = null)
    {
        return empty($this->settingPaths[$name]) ? null : $this->scopeConfig->getValue(
            $this->settingPaths[$name],
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get all module configuration values from db.
     * 
     * @return array
     */
    public function getAll()
    {
        return array_replace(
            $this->configurationPaths,
            array_map([$this, 'get'], $this->configurationPaths)
        );
    }
}