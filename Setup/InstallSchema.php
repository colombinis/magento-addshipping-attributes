<?php

namespace Sacsi\CustomAttribute\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->addEAV_piso($setup);
        $this->addEAV_dpto($setup);
        
        $setup->endSetup();
    }

    public function addEAV_piso($setup){

        $this->eavSetup->addAttribute(
            'customer_address',
            'piso',
            [
                'label' => 'Piso',
                'input' => 'text',
                'visible' => true,
                'required' => false,
                'position' => 150,
                'sort_order' => 150,
                'system'    => false
            ]
        );

        $customAttribute = $this->eavConfig->getAttribute(
                'customer_address',
                'piso'
        );
//SELECT DISTINCT form_code FROM `customer_form_attribute` WHERE 1
//['adminhtml_checkout', 'adminhtml_customer', 'adminhtml_customer_address', 'checkout_register', 'customer_account_create', 'customer_account_edit', 'customer_address_edit', 'customer_register_address']
        $customAttribute->setData(
            'used_in_forms',
            ['customer_address','customer_register_address','customer_address_edit','adminhtml_customer_address']
        );
    }

    public function addEAV_dpto($setup){

        $this->eavSetup->addAttribute(
            'customer_address',
            'dpto',
            [
                'label' => 'Dpto',
                'input' => 'text',
                'visible' => true,
                'required' => false,
                'position' => 150,
                'sort_order' => 150,
                'system'    => false
            ]
        );

        $customAttribute = $this->eavConfig->getAttribute(
                'customer_address',
                'dpto'
        );
//SELECT DISTINCT form_code FROM `customer_form_attribute` WHERE 1
//['adminhtml_checkout', 'adminhtml_customer', 'adminhtml_customer_address', 'checkout_register', 'customer_account_create', 'customer_account_edit', 'customer_address_edit', 'customer_register_address']
        $customAttribute->setData(
            'used_in_forms',
            ['customer_address','customer_register_address','customer_address_edit','adminhtml_customer_address']
        );
    }
}
