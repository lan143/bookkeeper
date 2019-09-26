<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m180925_185021_alter_transaction_tables
 */
class m180925_185021_alter_transaction_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%transactions}}', 'date', 'created_at');
        $this->addColumn('{{%transactions}}', 'date', $this->integer(11)->unsigned());

        $transactions = (new Query())
            ->select(['id', 'created_at'])
            ->from('{{%transactions}}')
            ->all();

        foreach ($transactions as $transaction) {
            $this->update('{{%transactions}}', [
                'date' => (int)$transaction['created_at'],
            ], [
                'id' => (int)$transaction['id'],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%transactions}}', 'date');
        $this->renameColumn('{{%transactions}}', 'created_at', 'date');
    }
}
