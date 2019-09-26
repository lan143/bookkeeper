<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;
use bulldozer\users\models\User;
use common\enums\BillTypes;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;

/**
 * Class Bill
 * @package common\ar
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $name
 * @property integer $currency_id
 * @property float $sum
 * @property array $params
 * @property integer $null_validation
 *
 * @property-read User $user
 * @property-read Currency $currency
 */
class Bill extends ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%bills}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCurrency(): ActiveQuery
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return BillTypes::getLabel($this->type);
    }
}