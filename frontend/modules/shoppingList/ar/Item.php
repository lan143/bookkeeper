<?php

namespace frontend\modules\shoppingList\ar;

use bulldozer\App;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;

/**
 * Class Item
 * @package frontend\modules\shoppingList\ar
 */
class Item extends \common\ar\shoppingList\Item
{
    /**
     * @return ActiveQuery
     */
    public static function find(): ActiveQuery
    {
        $query = parent::find();

        $query->andWhere(['user_id', App::$app->user->id]);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors[] = [
            'class' => BlameableBehavior::class,
            'createdByAttribute' => 'user_id',
            'updatedByAttribute' => false,
        ];

        return $behaviors;
    }
}