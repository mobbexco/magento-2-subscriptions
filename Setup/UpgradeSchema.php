<?php

namespace Mobbex\Subscriptions\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    /**
     * Create module tables using sql script.
     * 
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     */
    public function upgrade($setup)
    {
        $setup->getConnection()->query(
            file_get_contents(dirname(__FILE__) . '/install.sql')
        );
    }
}