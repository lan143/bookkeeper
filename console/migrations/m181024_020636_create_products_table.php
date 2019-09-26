<?php

use yii\db\Migration;

/**
 * Handles the creation of table `products`.
 */
class m181024_020636_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(11)->unsigned(),
            'updated_at' => $this->integer(11)->unsigned(),
            'category_id' => $this->integer(11)->unsigned(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->createTable('{{%product_categories}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer(11)->unsigned(),
            'updated_at' => $this->integer(11)->unsigned(),
            'name' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%product_categories}}');
    }
}
