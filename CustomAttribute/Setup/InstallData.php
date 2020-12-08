<?php

namespace Sacsi\CustomAttribute\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\AddressMetadataManagementInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetup;
    private $eavConfig;

    /**
     * @param EavSetup
     * @param Config
     */
    public function __construct(
            EavSetup $eavSetup,
            Config $config
        )
    {
        $this->eavSetup = $eavSetup;
        $this->eavConfig = $config;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->eavSetup->addAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
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
                AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                'piso'
        );
//SELECT DISTINCT form_code FROM `customer_form_attribute` WHERE 1
//['adminhtml_checkout', 'adminhtml_customer', 'adminhtml_customer_address', 'checkout_register', 'customer_account_create', 'customer_account_edit', 'customer_address_edit', 'customer_register_address']
        $customAttribute->setData(
            'used_in_forms',
            ['customer_address','customer_register_address','customer_address_edit','adminhtml_customer_address']
        );

        $customAttribute->save();

        $setup->endSetup();
    }

}
