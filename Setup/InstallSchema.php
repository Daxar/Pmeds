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

        if ($installer->getConnection()->isTableExists($installer->getTable(Config::TABLE_NAME))) {
            $installer->getConnection()->dropTable($installer->getTable(Config::TABLE_NAME));
        }

        $table = $installer->getConnection()->newTable(
                $installer->getTable(Config::TABLE_NAME)
            )->addColumn(
                Config::FIELD_ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                Config::FIELD_SORT_ORDER,
                Table::TYPE_INTEGER,
                null,
                [],
                'Sort Order'
            )->addColumn(
                Config::FIELD_TYPE_ID,
                Table::TYPE_INTEGER,
                null,
                [],
                'Question type id'
            )->addColumn(
                Config::FIELD_TITLE,
                Table::TYPE_TEXT,
                '64k',
                [],
                'Title'
            )->addColumn(
                Config::FIELD_REQUIRED,
                Table::TYPE_BOOLEAN,
                null,
                [],
                'Is required'
            )->addColumn(
                Config::FIELD_OPTIONS,
                Table::TYPE_TEXT,
                '64k',
                [],
                'Serialized options'
            )->addColumn(
                Config::FIELD_ANSWER,
                Table::TYPE_TEXT,
                '64k',
                [],
                'Answer'
            )->setComment(
                'Tingle Pmeds questions'
            );
        $installer->getConnection()
            ->createTable($table);

        $installer->endSetup();
    }
}
