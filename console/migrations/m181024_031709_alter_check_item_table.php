<?php

use yii\db\Migration;

/**
 * Class m181024_031709_alter_check_item_table
 */
class m181024_031709_alter_check_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%check_items}}', 'product_id', $this->integer(11)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%check_items}}', 'product_id');
    }
}
