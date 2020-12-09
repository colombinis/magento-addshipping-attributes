<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Sacsi\CustomAttribute\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

/**
 * @codeCoverageIgnore
 */
class Uninstall implements UninstallInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // $setup->startSetup();
        
        //TODO: how to drop EAV attribute from an entity 'customer_address'

        // //example drop entire table
        // $setup->getConnection()->dropTable($setup->getTable('oye_deliverydate_holiday'));

        // //example drop a column from a table
        // $setup->getConnection()->dropColumn(
        //     $setup->getTable('quote'),
        //     'delivery_date'
        // );

        // $setup->endSetup();
    }

}