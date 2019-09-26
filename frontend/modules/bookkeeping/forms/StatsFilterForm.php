<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use DateTime;

/**
 * Class StatsFilterForm
 * @package app\modules\bookkeeping\forms
 */
class StatsFilterForm extends Form
{
    /**
     * @var int
     */
    public $from_month;

    /**
     * @var int
     */
    public $from_year;

    /**
     * @var int
     */
    public $to_month;

    /**
     * @var int
     */
    public $to_year;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['from_month', 'required'],
            ['from_month', 'integer'],

            ['from_year', 'required'],
            ['from_year', 'integer'],

            ['to_month', 'required'],
            ['to_month', 'integer'],

            ['to_year', 'required'],
            ['to_year', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        $fromDate = (new DateTime())->modify('-3 month');
        $toDate = (new DateTime())->modify('first day of this month');

        $this->from_month = (int)$fromDate->format('m');
        $this->from_year = (int)$fromDate->format('Y');

        $this->to_month = (int)$toDate->format('m');
        $this->to_year = (int)$toDate->format('Y');
    }
}