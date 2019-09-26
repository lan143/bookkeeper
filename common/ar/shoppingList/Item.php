<?php

namespace common\ar\shoppingList;

use bulldozer\db\ActiveRecord;
use bulldozer\users\models\User;
use common\enums\BillTypes;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class Item
 * @package common\ar\shoppingList
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $list_id
 * @property string $name
 * @property float $quantity
 * @property integer $unit
 * @property integer $checked
 */
class Item extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%shopping_list_items}}';
    }
}