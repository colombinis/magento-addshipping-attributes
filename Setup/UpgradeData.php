<?php

namespace Sacsi\CustomAttribute\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;

class UpgradeData implements UpgradeDataInterface
{

    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $setup->startSetup();

        if (version_compare($context->getVersion(), "1.0.3", "<")) {
            $this->upgradeSchema103_quote_address($setup);
            $this->upgradeSchema103_sales_order_address($setup);
        }

        $setup->endSetup();
    }

    private function upgradeSchema103_quote_address(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $connection->addColumn(
            $setup->getTable('quote_address'),
            'piso',
            [
                'type' => 'text',
                'length' => 255,
                'comment' => 'the piso'
            ]
        );
        $connection->addColumn(
            $setup->getTable('quote_address'),
            'dpto',
            [
                'type' => 'text',
                'length' => 255,
                'comment' => 'the dpto'
            ]
        );
    }

    private function upgradeSchema103_sales_order_address(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();


        $connection->addColumn(
            $setup->getTable('sales_order_address'),
            'piso',
            [
                'type' => 'text',
                'length' => 255,
                'comment' => 'the piso'
            ]
        );
        $connection->addColumn(
            $setup->getTable('sales_order_address'),
            'dpto',
            [
                'type' => 'text',
                'length' => 255,
                'comment' => 'the dpto'
            ]
        );

    }

}
