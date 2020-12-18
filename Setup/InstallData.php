<?php

namespace Sacsi\CustomAttribute\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\AddressMetadataManagementInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
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

        $this->addAttributePiso();
        $this->addAttributeDpto();

        $setup->endSetup();
    }

    public function addAttributePiso(){

        $ENTITY_TYPE_ADDRESS =  AddressMetadataInterface::ENTITY_TYPE_ADDRESS ; //should return 'customer_address'
        $ATTRIBUTE_SET_ID_ADDRESS =  AddressMetadataInterface::ATTRIBUTE_SET_ID_ADDRESS ; //should return 2

        $entity = $this->eavConfig->getEntityType($ENTITY_TYPE_ADDRESS);
        $attributeSetId = $entity->getDefaultAttributeSetId();
        // $attributeSet = $this->attributeSetFactory->create();
        // $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $attributeGroupId=0;

        $this->eavSetup->addAttribute(
            $ENTITY_TYPE_ADDRESS,
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
        $this->eavSetup->addAttributeToSet(
            $ENTITY_TYPE_ADDRESS,
            $ATTRIBUTE_SET_ID_ADDRESS,
            null,
            'piso');

        $customAttribute = $this->eavConfig->getAttribute(
            $ENTITY_TYPE_ADDRESS,
                'piso'
        );
//SELECT DISTINCT form_code FROM `customer_form_attribute` WHERE 1
//['adminhtml_checkout', 'adminhtml_customer', 'adminhtml_customer_address', 'checkout_register', 'customer_account_create', 'customer_account_edit', 'customer_address_edit', 'customer_register_address']

/*
 'adminhtml_customer_address',
'customer_address_edit',
'customer_register_address'
*/
        $customAttribute->setData('used_in_forms',
            ['customer_register_address','customer_address_edit','adminhtml_customer_address']
        );
        $customAttribute->setData('attribute_set_id',$attributeSetId);
        $customAttribute->setData('attribute_group_id',$attributeGroupId);

        $customAttribute->save();
    }

    public function addAttributeDpto(){

        $ENTITY_TYPE_ADDRESS =  AddressMetadataInterface::ENTITY_TYPE_ADDRESS ; //should return 'customer_address'
        $ATTRIBUTE_SET_ID_ADDRESS =  AddressMetadataInterface::ATTRIBUTE_SET_ID_ADDRESS ; //should return 2

        $entity = $this->eavConfig->getEntityType($ENTITY_TYPE_ADDRESS);
        $attributeSetId = $entity->getDefaultAttributeSetId();
        // $attributeSet = $this->attributeSetFactory->create();
        // $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $attributeGroupId=0;

        $this->eavSetup->addAttribute(
            $ENTITY_TYPE_ADDRESS,
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
        $this->eavSetup->addAttributeToSet(
            $ENTITY_TYPE_ADDRESS,
            $ATTRIBUTE_SET_ID_ADDRESS,
            null,
            'dpto');

        $customAttribute = $this->eavConfig->getAttribute(
            $ENTITY_TYPE_ADDRESS,
                'dpto'
        );
//SELECT DISTINCT form_code FROM `customer_form_attribute` WHERE 1
//['adminhtml_checkout', 'adminhtml_customer', 'adminhtml_customer_address', 'checkout_register', 'customer_account_create', 'customer_account_edit', 'customer_address_edit', 'customer_register_address']

/*
 'adminhtml_customer_address',
'customer_address_edit',
'customer_register_address'
*/
        $customAttribute->setData('used_in_forms',
            ['customer_register_address','customer_address_edit','adminhtml_customer_address']
        );
        $customAttribute->setData('attribute_set_id',$attributeSetId);
        $customAttribute->setData('attribute_group_id',$attributeGroupId);

        $customAttribute->save();
    }
}
