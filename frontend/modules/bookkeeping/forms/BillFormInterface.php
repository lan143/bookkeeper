<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\db\ActiveRecord;

/**
 * Interface BillFormInterface
 * @package frontend\modules\bookkeeping\forms
 *
 * @mixin ActiveRecord
 */
interface BillFormInterface
{
    /**
     * @return array
     */
    public function getSavedAttributes(): array;
}