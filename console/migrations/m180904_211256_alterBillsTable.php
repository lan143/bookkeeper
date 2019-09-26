<?php

use yii\db\Migration;

/**
 * Class m180904_211256_alterBillsTable
 */
class m180904_211256_alterBillsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bills}}', 'sum', $this->decimal(10, 2)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bills}}', 'sum');
    }
}
