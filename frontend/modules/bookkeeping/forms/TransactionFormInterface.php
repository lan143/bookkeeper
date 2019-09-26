<?php

namespace frontend\modules\bookkeeping\forms;

use yii\db\ActiveRecord;

/**
 * Interface TransactionFormInterface
 * @package frontend\modules\bookkeeping\forms
 *
 * @mixin ActiveRecord
 */
interface TransactionFormInterface
{
    /**
     * @return array
     */
    public function getSavedAttributes(): array;
}