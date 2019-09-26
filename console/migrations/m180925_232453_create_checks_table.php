<?php

use yii\db\Migration;

/**
 * Handles the creation of table `checks`.
 */
class m180925_232453_create_checks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%checks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            't' => $this->integer(11)->unsigned()->notNull(),
            's' => $this->decimal(10, 2),
            'fn' => $this->bigInteger(20)->unsigned(),
            'i' => $this->integer(11)->unsigned(),
            'fp' => $this->integer(11)->unsigned(),
            'n' => $this->smallInteger(5)->unsigned(),
        ]);

        $this->addColumn('{{%transactions}}', 'check_id', $this->integer(11)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%checks}}');

        $this->dropColumn('{{%transactions}}', 'check_id');
    }
}
