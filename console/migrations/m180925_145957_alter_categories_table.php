<?php

use yii\db\Migration;

/**
 * Class m180925_145957_alter_categories_table
 */
class m180925_145957_alter_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'is_deleted', $this->boolean()->defaultValue(0));
        $this->alterColumn('{{%categories}}', 'default_category_id', $this->integer(11)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%categories}}', 'is_deleted');
        $this->alterColumn('{{%categories}}', 'default_category_id', $this->integer(11)->unsigned()->notNull());
    }
}
