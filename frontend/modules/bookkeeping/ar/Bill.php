<?php

namespace frontend\modules\bookkeeping\ar;

use BankDb\BankDb;
use BankDb\BankDbException;
use bulldozer\App;
use yii\db\ActiveQuery;

/**
 * Class Bill
 * @package frontend\modules\bookkeeping\ar
 *
 * @mixin \common\ar\Bill
 */
class Bill extends \common\ar\Bill
{
    /**
     * @return ActiveQuery
     */
    public static function findWithUser(): ActiveQuery
    {
        $query = static::find();
        $query->andWhere(['user_id' => App::$app->user->id]);

        return $query;
    }

    /**
     * @return array|null
     */
    public function getCardInfo(): ?array
    {
        if (isset($this->params['card_no'])) {
            try {
                $bankDb = new BankDb();
                $bank_info = $bankDb->getBankInfo($this->params['card_no']);

                $result = [
                    'is_unknown' => $bank_info->isUnknown(), // is bank unknown
                    'name' => $bank_info->getTitle(true),
                    'color' => $bank_info->getColor(),
                    'type' => $bank_info->getCardType(),
                ];

                return $result;
            } catch (BankDbException $e) {
                return null;
            }
        } else {
            return null;
        }
    }
}
