<?php

use yii\db\Migration;

/**
 * Class m181023_194328_alter_checks_table
 */
class m181023_194328_alter_checks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%checks}}', 'status', $this->smallInteger(2)->unsigned()->defaultValue(1));
        $this->addColumn('{{%checks}}', 'raw_response', $this->json());
        $this->addColumn('{{%checks}}', 'shop_name', $this->string(500));
        $this->addColumn('{{%checks}}', 'shop_address', $this->string(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%checks}}', 'status');
        $this->dropColumn('{{%checks}}', 'raw_response');
        $this->dropColumn('{{%checks}}', 'shop_name');
        $this->dropColumn('{{%checks}}', 'shop_address');
    }
}
