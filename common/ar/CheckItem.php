<?php

namespace common\ar;

use bulldozer\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class CheckItem
 * @package common\ar
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $check_id
 * @property string $name
 * @property float $quantity
 * @property float $sum
 * @property float $price
 * @property float $nds10
 * @property integer $product_id
 *
 * @property-read Product $product
 */
class CheckItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%check_items}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}