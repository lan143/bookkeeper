<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;
use common\enums\TransactionTypes;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;

/**
 * Class Category
 * @package common\ar
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $default_category_id
 * @property integer $transaction_type
 * @property string $name
 * @property integer $is_deleted
 *
 * @property-read DefaultCategory $default
 * @property-read string $transactionTypeName
 */
class Category extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ]
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%categories}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getDefault(): ActiveQuery
    {
        return $this->hasOne(DefaultCategory::class, ['id' => 'default_category_id']);
    }

    /**
     * @return string
     */
    public function getTransactionTypeName(): string
    {
        return TransactionTypes::getLabel($this->transaction_type);
    }
}