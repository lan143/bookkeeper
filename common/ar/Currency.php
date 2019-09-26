<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;

/**
 * Class Currency
 * @package common\ar
 *
 * @property integer $id
 * @property string $name
 */
class Currency extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%currencies}}';
    }
}