<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;
use DateTime;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class Check
 * @package common\ar
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $t
 * @property integer $s
 * @property integer $fn
 * @property integer $i
 * @property integer $fp
 * @property integer $n
 * @property integer $status
 * @property array $raw_response
 * @property string $shop_name
 * @property string $shop_address
 * @property integer $update_categories_status
 *
 * @property-read CheckItem[] $items
 */
class Check extends ActiveRecord
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
        return '{{%checks}}';
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return (new DateTime())->setTimestamp($this->t);
    }

    /**
     * @return ActiveQuery
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(CheckItem::class, ['check_id' => 'id']);
    }
}