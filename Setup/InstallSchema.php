<?php declare(strict_types=1);
namespace Tingle\Pmeds\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Tingle\Pmeds\Api\Data\QuestionsInterface as QuestionsConfig;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterface as ProductQuestionsConfig;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $this->createQuestionsTable($installer);
        $this->createProductQuestionsTable($installer);

        $installer->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @throws \Exception
     */
    private function createQuestionsTable($installer)
    {
        if ($installer->getConnection()->isTableExists($installer->getTable(QuestionsConfig::TABLE_NAME))) {
            $installer->getConnection()->dropTable($installer->getTable(QuestionsConfig::TABLE_NAME));
        }

        $table = $installer->getConnection()->newTable(
            $installer->getTable(QuestionsConfig::TABLE_NAME)
        )->addColumn(
            QuestionsConfig::FIELD_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            QuestionsConfig::FIELD_SORT_ORDER,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Sort Order'
        )->addColumn(
            QuestionsConfig::FIELD_TYPE_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Question type id'
        )->addColumn(
            QuestionsConfig::FIELD_TITLE,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => false],
            'Title'
        )->addColumn(
            QuestionsConfig::FIELD_REQUIRED,
            Table::TYPE_BOOLEAN,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Is required'
        )->addColumn(
            QuestionsConfig::FIELD_OPTIONS,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Serialized options'
        )->addColumn(
            QuestionsConfig::FIELD_ANSWER,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => true],
            'Answer'
        )->setComment(
            'Tingle Pmeds questions'
        );
        $installer->getConnection()
            ->createTable($table);
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @throws \Exception
     */
    private function createProductQuestionsTable($installer)
    {
        if ($installer->getConnection()->isTableExists($installer->getTable(ProductQuestionsConfig::TABLE_NAME))) {
            $installer->getConnection()->dropTable($installer->getTable(ProductQuestionsConfig::TABLE_NAME));
        }

        $table = $installer->getConnection()->newTable(
            $installer->getTable(ProductQuestionsConfig::TABLE_NAME)
        )->addColumn(
            ProductQuestionsConfig::FIELD_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            ProductQuestionsConfig::FIELD_STORE_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Store ID'
        )->addColumn(
            ProductQuestionsConfig::FIELD_PRODUCT_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product ID'
        )->addColumn(
            ProductQuestionsConfig::FIELD_SELECTED_QUESTION_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Selected question ID'
        )->setComment(
            'Tingle Pmeds product questions'
        );
        $installer->getConnection()
            ->createTable($table);
    }
}
