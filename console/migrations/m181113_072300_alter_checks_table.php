<?php

use yii\db\Migration;

/**
 * Class m181113_072300_alter_checks_table
 */
class m181113_072300_alter_checks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%checks}}', 'update_categories_status', $this->smallInteger(2)->unsigned()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%checks}}', 'update_categories_status');
    }
}
