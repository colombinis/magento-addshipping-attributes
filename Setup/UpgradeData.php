<?php

namespace Sacsi\CustomAttribute\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class UpgradeData implements UpgradeDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $setup->startSetup();

        if (version_compare($context->getVersion(), "1.0.2", "<")) {
            $this->upgradeSchema102($setup);
        }

        $setup->endSetup();
    }


    private function upgradeSchema102(ModuleDataSetupInterface $setup)
    {
        $quoteAddressTable = $setup->getTable('quote_address');
        $setup->run("ALTER TABLE " . $quoteAddressTable . " ADD `piso` varchar(250) NOT NULL");
        $setup->run("ALTER TABLE " . $quoteAddressTable . " ADD `dpto` varchar(250) NOT NULL");

        $salesOrderAddressTable = $setup->getTable('sales_order_address');
        $setup->run("ALTER TABLE " . $salesOrderAddressTable . " ADD `piso` varchar(250) NOT NULL");
        $setup->run("ALTER TABLE " . $salesOrderAddressTable . " ADD `dpto` varchar(250) NOT NULL");
    }
}
