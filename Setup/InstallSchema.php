<?php declare(strict_types=1);
namespace Tingle\Pmeds\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
                $installer->getTable(Config::TABLE_NAME)
            )->addColumn(
                Config::FIELD_ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'sku',
                Table::TYPE_TEXT,
                64,
                [],
                'SKU'
            )->setComment(
                'Tingle Pmeds questions'
            );
        $installer->getConnection()
            ->createTable($table);

        $installer->endSetup();
    }
}
