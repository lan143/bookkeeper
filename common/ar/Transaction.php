<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;
use bulldozer\users\models\User;
use common\enums\TransactionTypes;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class Transaction
 * @package common\ar
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $source_bill_id
 * @property integer $destination_bill_id
 * @property integer $type
 * @property float $sum
 * @property integer $created_at
 * @property string $comment
 * @property integer $category_id
 * @property integer $date
 * @property integer $check_id
 *
 * @property-read User $user
 * @property-read Bill $sourceBill
 * @property-read Bill $destinationBill
 * @property-read Category $category
 * @property-read Check $check
 */
class Transaction extends ActiveRecord
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
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%transactions}}';
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
    public function getSourceBill(): ActiveQuery
    {
        return $this->hasOne(Bill::class, ['id' => 'source_bill_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDestinationBill(): ActiveQuery
    {
        return $this->hasOne(Bill::class, ['id' => 'destination_bill_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCheck(): ActiveQuery
    {
        return $this->hasOne(Check::class, ['id' => 'check_id']);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return TransactionTypes::getLabel($this->type);
    }
}