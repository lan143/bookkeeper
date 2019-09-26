<?php

namespace frontend\modules\bookkeeping\ar;

use bulldozer\App;
use yii\db\ActiveQuery;

/**
 * Class Category
 * @package frontend\modules\bookkeeping\ar
 */
class Category extends \common\ar\Category
{
    /**
     * @return ActiveQuery
     */
    public static function findWithUser(): ActiveQuery
    {
        $query = static::find();
        $query
            ->andWhere(['user_id' => App::$app->user->id])
            ->andWhere(['is_deleted' => 0]);

        return $query;
    }
}