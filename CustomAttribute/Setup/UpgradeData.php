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

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $this->upgradeSchema101($setup);
        }

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

    private function upgradeSchema101(ModuleDataSetupInterface $setup)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(\Magento\Customer\Model\Indexer\Address\AttributeProvider::ENTITY, 'dpto', [
            'label' => 'Dpto',
            'input' => 'text',
            'type' => 'varchar',
            'source' => '',
            'required' => false,
            'position' => 150,
            'visible' => true,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false,
            'backend' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'dpto')
            ->addData(['used_in_forms' =>
            ['customer_address', 'customer_register_address', 'customer_address_edit', 'adminhtml_customer_address']]);
        $attribute->save();
    }
}
