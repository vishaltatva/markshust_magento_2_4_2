<?php
/**
 * Sparsh ReviewReminder
 * php version 7.0.31
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
namespace Sparsh\ReviewReminder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @category Sparsh
 * @package   Sparsh_ReviewReminder
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install table
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup   Setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context Context
     *
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('sparsh_review_reminder_log')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('sparsh_review_reminder_log')
            )->addColumn(
                'review_reminder_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['identity' => true, 'nullable' => false, 'primary' => true,'unsigned' => true],
                'Review Reminder Id'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                55,
                [],
                'Order Id'
            )->addColumn(
                'order_increment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                [],
                'Order Increment Id'
            )->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                [],
                'Receiver'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                [],
                'Customer name'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                [],
                'Status'
            )->addColumn(
                'schedule_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'Schedule At'
            )->addColumn(
                'no_of_time_sent',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 0],
                'Number Of Time Sent'
            )->addColumn(
                'customer_group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [],
                'Customer Group Id'
            )->addColumn(
                'product',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [],
                'Product'
            )->addColumn(
                'errormsg',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                ['nullable' => true, 'default' => NULL],
                'Error Message'
            )->addColumn(
                'creation_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Event Created DateTime'
            )->addIndex(
                $installer->getIdxName(
                    'sparsh_review_reminder_log',
                    ['review_reminder_id']
                ),
                ['review_reminder_id']
            )->addIndex(
                $installer->getIdxName(
                    'sparsh_review_reminder_log',
                    ['email'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['email']
            )->addIndex(
                $installer->getIdxName(
                    'sparsh_review_reminder_log',
                    ['order_increment_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['order_increment_id']
            )->setComment(
                'Sparsh Review Reminder Table'
            );
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
