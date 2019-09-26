<?php

namespace common\components\report;

use bulldozer\App;
use DateTime;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\BaseObject;

/**
 * Class IntervalService
 * @package app\components\report
 */
class IntervalService extends BaseObject
{
    /**
     * @var Interval[]
     */
    private $intervals = [];

    /**
     * @var DateTime
     */
    private $from;

    /**
     * @var DateTime
     */
    private $to;

    /**
     * @var int
     */
    private $step;

    /**
     * Intervals constructor.
     * @param DateTime $from
     * @param DateTime $to
     * @param int $step
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(DateTime $from, DateTime $to, int $step, array $config = [])
    {
        parent::__construct($config);

        $this->from = $from;
        $this->to = $to;

        if (in_array($step, Steps::getConstantsByName())) {
            $this->step = $step;
        } else {
            throw new InvalidConfigException('Step incorrect');
        }

        $this->generateIntervals();
    }

    /**
     * @param DateTime $date
     * @return int|null
     */
    public function getIntervalKey(DateTime $date): ?int
    {
        foreach ($this->intervals as $key => $interval) {
            if ($interval->getFromDateTime() <= $date && $interval->getToDateTime() >= $date) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @return int
     */
    public static function getDefaultStep(DateTime $from, DateTime $to) : int
    {
        $from = $from->getTimestamp();
        $to = $to->getTimestamp();
        $interval = $to - $from;
        $step = null;

        if ($interval / 31536000 > 1) {
            return Steps::YEAR;
        } else if ($interval / 7776000 > 2) {
            return Steps::QUART;
        } else {
            return Steps::MONTH;
        }
    }

    /**
     * @return Interval[]
     */
    public function getIntervals(): array
    {
        return $this->intervals;
    }

    /**
     * Generate intervals
     * @throws InvalidConfigException
     */
    protected function generateIntervals() : void
    {
        $from = $this->from->getTimestamp();
        $to = $this->to->getTimestamp();
        $start = $step = null;

        if ($this->step === Steps::YEAR) {
            $start = strtotime('1 january', $from);
            $step = '+1 year';
        } else if ($this->step === Steps::QUART) {
            $start = strtotime('01.'
                . str_pad(
                    3 * (ceil((int)date('m', $from) / 3) - 1) + 1,
                    2,
                    '0',
                    STR_PAD_LEFT) . '.' . date('Y', $from), $from);
            $step = '+3 month';
        } else if ($this->step === Steps::WEEK) {
            $start = strtotime('first day of this week midnight', $from);
            $step = '+1 week';
        } else if ($this->step === Steps::DAY) {
            $start = strtotime('midnight', $from);
            $step = '+1 day';
        } else if ($this->step === Steps::MONTH) {
            $start = strtotime('first day of this month midnight', $from);
            $step = '+1 month';
        }

        for ($next = $start; $start <= $to; $start = $next) {
            $next = strtotime($step, $next);

            $this->intervals[] = App::createObject([
                'class' => Interval::class,
                'name' => ($this->step === Steps::YEAR ? date('Y', $start):
                    ($this->step === Steps::QUART ? (floor((int)date('m', $start) / 3) + 1)
                        . Yii::t('reports', '\'th quarter ') . date('Y', $start) :
                        ($this->step === Steps::WEEK ? date('d.m.Y - ', $start) . date('d.m.Y', $next):
                            ($this->step === Steps::DAY ? date('d.m.Y ', $start) . date('l', $start) :
                                date('F', $start) . ' ' . date('Y', $start))))),
                'from' => date('Y-m-d H:i:s', $start),
                'to' => date('Y-m-d H:i:s', $next - 1),
            ]);
        }
    }
}