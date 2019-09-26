<?php

use yii\db\Migration;

/**
 * Class m180904_172518_create_base_tables
 */
class m180904_172518_create_base_tables extends Migration
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

        $this->createTable('{{%bills}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'type' => $this->smallInteger(3)->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'currency_id' => $this->integer(11)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%currencies}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->insert('{{%currencies}}', [
            'id' => 1,
            'name' => 'Рубль',
        ]);

        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'source_bill_id' => $this->integer(11)->unsigned(),
            'destination_bill_id' => $this->integer(11)->unsigned(),
            'type' => $this->smallInteger(2)->unsigned()->notNull(),
            'sum' => $this->decimal(10, 2)->unsigned()->notNull(),
            'date' => $this->integer(11)->unsigned()->notNull(),
            'comment' => $this->text(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bills}}');
        $this->dropTable('{{%currencies}}');
        $this->dropTable('{{%transactions}}');
    }
}
