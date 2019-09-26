<?php

use yii\db\Migration;

/**
 * Handles the creation of table `check_items`.
 */
class m181023_203318_create_check_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%check_items}}', [
            'id' => $this->primaryKey(),
            'check_id' => $this->integer(11)->unsigned()->notNull(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'name' => $this->string(800),
            'quantity' => $this->decimal(10, 6)->unsigned(),
            'sum' => $this->decimal(10, 2)->unsigned(),
            'price' => $this->decimal(10, 2)->unsigned(),
            'nds10' => $this->decimal(10, 2)->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%check_items}}');
    }
}
