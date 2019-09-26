<?php

use yii\db\Migration;

/**
 * Class m181026_020509_update_bills_table
 */
class m181026_020509_update_bills_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bills}}', 'null_validation', $this->boolean()->defaultValue(1));
        $this->alterColumn('{{%bills}}', 'sum', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bills}}', 'null_validation');
        $this->alterColumn('{{%bills}}', 'sum', $this->decimal(10, 2)->unsigned());
    }
}
