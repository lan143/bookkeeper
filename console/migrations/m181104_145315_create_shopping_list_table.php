<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shopping_list`.
 */
class m181104_145315_create_shopping_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shopping_lists}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->notNull(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->smallInteger(2)->unsigned()->notNull(),
        ]);

        $this->createTable('{{%shopping_list_items}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->notNull(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'list_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'name' => $this->string(500)->notNull(),
            'quantity' => $this->decimal(10, 5)->unsigned()->notNull(),
            'unit' => $this->smallInteger(3)->unsigned()->notNull(),
            'checked' => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shopping_list}}');
        $this->dropTable('{{%shopping_list_items}}');
    }
}
