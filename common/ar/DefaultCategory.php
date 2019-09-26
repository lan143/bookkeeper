<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;

/**
 * Class DefaultCategory
 * @package common\ar
 *
 * @property integer $id
 * @property integer $transaction_type
 * @property string $name
 */
class DefaultCategory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%default_categories}}';
    }
}