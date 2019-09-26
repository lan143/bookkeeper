<?php

namespace frontend\modules\bookkeeping\ar;

use bulldozer\App;
use yii\db\ActiveQuery;

/**
 * Class Check
 * @package frontend\modules\bookkeeping\ar
 */
class Check extends \common\ar\Check
{
    /**
     * @return ActiveQuery
     */
    public static function findWithUser(): ActiveQuery
    {
        $query = static::find();
        $query
            ->andWhere(['user_id' => App::$app->user->id]);

        return $query;
    }
}