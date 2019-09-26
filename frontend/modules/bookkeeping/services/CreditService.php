<?php

namespace frontend\modules\bookkeeping\services;

use DateTime;
use frontend\modules\bookkeeping\ar\Bill;

/**
 * Class CreditService
 * @package frontend\modules\bookkeeping\services
 */
class CreditService
{
    public function getCreditStats(Bill $bill): array
    {
        $data = $this->buildScheduleOfPayments($bill);
        $currentLine = false;
        $result = [
            'payed_count' => 0,
            'payed_sum' => 0,
            'payed_percents' => 0,
            'payed_main_dept' => 0,
            'remains_count' => 0,
            'remains_sum' => 0,
            'remains_percents' => 0,
            'remains_main_dept' => 0,
        ];

        foreach ($data as $date => $_data) {
            if ((new DateTime($date))->format('m.Y') == date('m.Y')) {
                $currentLine = true;
            }

            if (!$currentLine) {
                $result['payed_count']++;
                $result['payed_sum'] += $_data['full'];
                $result['payed_percents'] += $_data['percents_debt'];
                $result['payed_main_dept'] += $_data['main_debt'];
            } else {
                $result['remains_count']++;
                $result['remains_sum'] += $_data['full'];
                $result['remains_percents'] += $_data['percents_debt'];
                $result['remains_main_dept'] += $_data['main_debt'];
            }
        }

        return $result;
    }

    /**
     * @param Bill $bill
     * @return array
     */
    public function buildScheduleOfPaymentsCompact(Bill $bill): array
    {
        $data = $this->buildScheduleOfPayments($bill);
        $result = [];
        $currentLine = false;

        foreach ($data as $date => $_data) {
            if ((new DateTime($date))->format('m.Y') == date('m.Y')) {
                $currentLine = true;
            }

            if ($currentLine) {
                $result[$date] = $_data;
            }
        }

        return $result;
    }

    /**
     * @param Bill $bill
     * @return array
     */
    public function buildScheduleOfPayments(Bill $bill): array
    {
        $data = [];

        $creditSum = $bill->params['sum'];
        $date = new DateTime($bill->params['date_of_issue']);

        for ($i = 0; $i < $bill->params['term']; $i++) {
            $date->modify('+1 month');

            $percents = $bill->params['percents'] / 100;
            $sep = round(($bill->params['sum'] * $percents / 12) / (1 - (1 / pow(1 + $percents / 12, $bill->params['term']))), 2);
            $sep += $bill->params['month_error'];

            $prevMonth = clone $date;
            $prevMonth->modify('-1 month');

            $daysInMonth = (int)$prevMonth->format('t');
            $daysInYear = $date->format('L') ? 366 : 365;

            $sep_percents = round(($creditSum * $percents * $daysInMonth) / $daysInYear, 2);
            $sep_main_dept = $sep - $sep_percents;

            $creditSum -= $sep_main_dept;

            if ($i == $bill->params['term'] - 1) {
                $creditSum = 0;
            }

            $data[$date->format('d.m.Y')] = [
                'sep' => $sep,
                'main_debt' => $sep_main_dept,
                'percents_debt' => $sep_percents,
                'add' => $bill->params['additional_payments'] ?: 0,
                'full' => $sep + $bill->params['additional_payments'],
                'creditSum' => $creditSum,
            ];
        }

        return $data;
    }
}