<?php declare(strict_types=1);
namespace Tingle\Pmeds\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Tingle\Pmeds\Api\Data\QuestionsInterface as QuestionsConfig;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterface as ProductQuestionsConfig;
use Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface as FormDataConfig;

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
        $this->createQuestionnaireFormDataTable($installer);

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
            Table::TYPE_SMALLINT,
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
        )->addForeignKey(
            $installer->getFkName(ProductQuestionsConfig::TABLE_NAME, ProductQuestionsConfig::FIELD_STORE_ID, 'store', ProductQuestionsConfig::FIELD_STORE_ID),
            ProductQuestionsConfig::FIELD_STORE_ID,
            $installer->getTable('store'),
            ProductQuestionsConfig::FIELD_STORE_ID,
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(ProductQuestionsConfig::TABLE_NAME, ProductQuestionsConfig::FIELD_PRODUCT_ID, 'catalog_product_entity', 'entity_id'),
            ProductQuestionsConfig::FIELD_PRODUCT_ID,
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(ProductQuestionsConfig::TABLE_NAME, ProductQuestionsConfig::FIELD_SELECTED_QUESTION_ID, QuestionsConfig::TABLE_NAME, QuestionsConfig::FIELD_ID),
            ProductQuestionsConfig::FIELD_SELECTED_QUESTION_ID,
            $installer->getTable(QuestionsConfig::TABLE_NAME),
            QuestionsConfig::FIELD_ID,
            Table::ACTION_CASCADE
        )->setComment(
            'Tingle Pmeds product questions'
        );
        $installer->getConnection()
            ->createTable($table);
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @throws \Exception
     */
    private function createQuestionnaireFormDataTable($installer)
    {
        if ($installer->getConnection()->isTableExists($installer->getTable(FormDataConfig::TABLE_NAME))) {
            $installer->getConnection()->dropTable($installer->getTable(FormDataConfig::TABLE_NAME));
        }

        $table = $installer->getConnection()->newTable(
            $installer->getTable(FormDataConfig::TABLE_NAME)
        )->addColumn(
            FormDataConfig::FIELD_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            FormDataConfig::FIELD_ORDER_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Order ID'
        )->addColumn(
            FormDataConfig::FIELD_CREATED_AT,
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            FormDataConfig::FIELD_CUSTOMER_IP_ADDRESS,
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Customer IP address'
        )->addColumn(
            FormDataConfig::FIELD_QUESTIONNAIRE_FORM_DATA,
            Table::TYPE_TEXT,
            '64k',
            ['nullable' => false],
            'Serialized form data'
        )->addForeignKey(
            $installer->getFkName(FormDataConfig::TABLE_NAME, FormDataConfig::FIELD_ORDER_ID, 'sales_order', 'entity_id'),
            FormDataConfig::FIELD_ORDER_ID,
            $installer->getTable('sales_order'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Tingle Pmeds passed questionnaire form data'
        );
        $installer->getConnection()
            ->createTable($table);
    }
}
