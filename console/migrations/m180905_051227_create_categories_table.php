<?php

use yii\db\Migration;

/**
 * Handles the creation of table `categories`.
 */
class m180905_051227_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'default_category_id' => $this->integer(11)->unsigned()->notNull(),
            'transaction_type' => $this->smallInteger(2)->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%default_categories}}', [
            'id' => $this->primaryKey(),
            'transaction_type' => $this->smallInteger(2)->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 1,
            'name' => 'Зарплата',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 1,
            'name' => 'Подарок',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 1,
            'name' => 'Шабашка',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 2,
            'name' => 'Продукты',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 2,
            'name' => 'Автомобиль',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 2,
            'name' => 'Общественный транспорт',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 2,
            'name' => 'Медицина',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 2,
            'name' => 'Развлечения',
        ]);

        $this->insert('{{%default_categories}}', [
            'transaction_type' => 2,
            'name' => 'Коммунальные платежи',
        ]);

        $this->addColumn('{{%transactions}}', 'category_id', $this->integer(11)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%categories}}');
        $this->dropTable('{{%default_categories}}');
        $this->dropColumn('{{%transactions}}', 'category_id');
    }
}
