<?php

use yii\db\Migration;

/**
 * Class m180925_115846_alter_bills_table
 */
class m180925_115846_alter_bills_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bills}}', 'params', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bills}}', 'params');
    }
}
