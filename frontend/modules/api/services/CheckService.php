<?php

namespace frontend\modules\api\services;

use bulldozer\App;
use console\tasks\GetCheckInfoJob;
use DateTime;
use frontend\modules\bookkeeping\ar\Check;
use yii\queue\Queue;

/**
 * Class CheckService
 * @package frontend\modules\api\services
 */
class CheckService
{
    /**
     * @param string $t
     * @param float $s
     * @param int $fn
     * @param int $i
     * @param int $fp
     * @param int $n
     * @return Check
     * @throws \yii\base\InvalidConfigException
     */
    public function findOrCreate(string $t, float $s, int $fn, int $i, int $fp, int $n): Check
    {
        $check = $this->find($t, $s, $fn, $i, $fp, $n);

        if ($check === null) {
            $check = $this->createCheck($t, $s, $fn, $i, $fp, $n);
        }

        return $check;
    }

    /**
     * @param string $t
     * @param float $s
     * @param int $fn
     * @param int $i
     * @param int $fp
     * @param int $n
     * @return Check
     */
    protected function find(string $t, float $s, int $fn, int $i, int $fp, int $n): ?Check
    {
        return Check::findWithUser()
            ->andWhere(['t' => (new DateTime($t))->getTimestamp()])
            ->andWhere(['s' => $s])
            ->andWhere(['fn' => $fn])
            ->andWhere(['i' => $i])
            ->andWhere(['fp' => $fp])
            ->andWhere(['n' => $n])
            ->one();
    }

    /**
     * @param string $t
     * @param float $s
     * @param int $fn
     * @param int $i
     * @param int $fp
     * @param int $n
     * @return Check
     * @throws \yii\base\InvalidConfigException
     */
    protected function createCheck(string $t, float $s, int $fn, int $i, int $fp, int $n): Check
    {
        /** @var Check $check */
        $check = App::createObject([
            'class' => Check::class,
            't' => (new DateTime($t))->getTimestamp(),
            's' => $s,
            'fn' => $fn,
            'i' => $i,
            'fp' => $fp,
            'n' => $n,
        ]);

        $check->save();

        /** @var Queue $queue */
        $queue = App::$app->get('queue');

        $queue->push(App::createObject([
            'class' => GetCheckInfoJob::class,
            'check_id' => $check->id,
        ]));

        return $check;
    }
}