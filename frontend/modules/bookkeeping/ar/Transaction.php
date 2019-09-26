<?php

namespace frontend\modules\bookkeeping\ar;

use bulldozer\App;
use yii\db\ActiveQuery;

/**
 * Class Transaction
 * @package frontend\modules\bookkeeping\ar
 */
class Transaction extends \common\ar\Transaction
{
    /**
     * @return ActiveQuery
     */
    public static function find(): ActiveQuery
    {
        $query = parent::find();

        $query->andWhere(['user_id' => App::$app->user->id]);

        return $query;
    }
}