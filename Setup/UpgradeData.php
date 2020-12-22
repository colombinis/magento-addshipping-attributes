<?php

namespace Sacsi\CustomAttribute\Setup;
//namespace Magento\Customer\Setup;

use Magento\Customer\Model\Customer;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * Quote setup factory
     *
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * Sales setup factory
     *
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param QuoteSetupFactory $setupFactory
     * @param SalesSetupFactory $salesSetupFactory
     * @param IndexerRegistry $indexerRegistry
     * @param \Magento\Eav\Model\Config $eavConfig
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        QuoteSetupFactory $setupFactory,
        SalesSetupFactory $salesSetupFactory,
        IndexerRegistry $indexerRegistry,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->quoteSetupFactory = $setupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->indexerRegistry = $indexerRegistry;
        $this->eavConfig = $eavConfig;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $this->add2CustomerAddress($setup);
            $this->updateUsedInforms($setup);
            $this->add2QuoteAddress($setup);
            $this->add2SalesOrderAddress($setup);
        }

        // if (version_compare($context->getVersion(), '1.0.7', '<')) {
        //     $this->updateAttributes107($setup);
        // }

        //$indexer = $this->indexerRegistry->get(Customer::CUSTOMER_GRID_INDEXER_ID);
        //$indexer->reindexAll();
        $this->eavConfig->clear();
        $setup->endSetup();
    }

    function add2CustomerAddress($setup)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'piso',
            [
                'type' => 'static',
                'label' => 'Piso',
                'input' => 'text',
                'required' => false,
                'sort_order' => 150,
                'visible' => true,
                'system' => false,
                'is_user_defined' => true,
            ]
        );

        $customerSetup->addAttribute(
            \Magento\Customer\Api\AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'dpto',
            [
                'type' => 'static',
                'label' => 'Dpto',
                'input' => 'text',
                'required' => false,
                'sort_order' => 150,
                'visible' => true,
                'system' => false,
                'is_user_defined' => true,
            ]
        );

        //add columnos to customer_address_entity
        $setup->getConnection()->addColumn(
            $setup->getTable('customer_address_entity'),
            'piso',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'piso custom attribute'
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('customer_address_entity'),
            'dpto',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'dpto custom attribute'
            ]
        );

    }

    function add2QuoteAddress($setup)
    {
        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);

        /**
         * Install eav entity types to the eav/entity_type table
         */
        $attributes = [
            'piso' => ['type' => Table::TYPE_TEXT],
            'dpto' => ['type' => Table::TYPE_TEXT],
        ];

        foreach ($attributes as $attributeCode => $attributeParams) {
            $quoteSetup->addAttribute('quote_address', $attributeCode, $attributeParams);
        }
    }

    function add2SalesOrderAddress($setup)
    {
        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        /**
         * Install eav entity types to the eav/entity_type table
         */
        $attributes = [
            'piso' => ['type' => Table::TYPE_TEXT],
            'dpto' => ['type' => Table::TYPE_TEXT],
        ];

        foreach ($attributes as $attributeCode => $attributeParams) {
            $salesSetup->addAttribute('order_address', $attributeCode, $attributeParams);
        }
    }

    function updateUsedInforms($setup){
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $pisoAttribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'piso');
        $pisoAttribute->setData(
            'used_in_forms',
            ['customer_register_address','customer_address_edit','adminhtml_customer_address']
        );
        $pisoAttribute->save();

        $dptoAttribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'dpto');
        $dptoAttribute->setData(
            'used_in_forms',
            ['customer_register_address','customer_address_edit','adminhtml_customer_address']
        );
        $dptoAttribute->save();
    }

    function updateAttributes107($setup){
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $entityAttributes = [
            'customer_address' => [
                'piso' => [
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'is_searchable_in_grid' => true,
                    'is_user_defined' => true,
                ],
                'dpto' => [
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'is_searchable_in_grid' => true,
                    'is_user_defined' => true,
                ],
            ],
        ];
        $this->upgradeAttributes($entityAttributes, $customerSetup);

    }

    protected function upgradeAttributes(array $entityAttributes, CustomerSetup $customerSetup)
    {
        foreach ($entityAttributes as $entityType => $attributes) {
            foreach ($attributes as $attributeCode => $attributeData) {
                $attribute = $customerSetup->getEavConfig()->getAttribute($entityType, $attributeCode);
                foreach ($attributeData as $key => $value) {
                    $attribute->setData($key, $value);
                }
                $attribute->save();
            }
        }
    }

}
