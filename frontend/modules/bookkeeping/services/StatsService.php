<?php

namespace frontend\modules\bookkeeping\services;

use bulldozer\App;
use common\ar\ProductCategory;
use common\components\report\IntervalService;
use common\components\report\Steps;
use common\enums\TransactionTypes;
use DateTime;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\ar\Transaction;
use frontend\modules\bookkeeping\forms\StatsFilterForm;

/**
 * Class StatsService
 * @package frontend\modules\bookkeeping\services
 */
class StatsService
{
    /**
     * @var IntervalService
     */
    private $intervalService;

    /**
     * @param StatsFilterForm $filterForm
     * @param Category[] $categories
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getDebitCreditStats(StatsFilterForm $filterForm, array $categories): array
    {
        $dateFrom = (new DateTime($filterForm->from_year . '-' .$filterForm->from_month . '-01'))
            ->modify('first day of this month');
        $dateTo = (new DateTime($filterForm->to_year . '-' . $filterForm->to_month . '-01'))
            ->modify('last day of this month');

        $this->intervalService = new IntervalService($dateFrom, $dateTo, Steps::MONTH);

        $query = Transaction::find()
            ->andWhere(['type' => [TransactionTypes::DEBIT, TransactionTypes::CREDIT]])
            ->andWhere(['>=', 'date', $dateFrom->getTimestamp()])
            ->andWhere(['<=', 'date', $dateTo->getTimestamp()]);

        $data = [];

        foreach ([TransactionTypes::DEBIT, TransactionTypes::CREDIT] as $transactionType) {
            $data[$transactionType] = [
                'sum' => 0,
                'intervals' => [],
            ];

            foreach ($this->getIntervals() as $key => $interval) {
                $data[$transactionType]['intervals'][$key] = [
                    'sum' => 0,
                    'categories' => [],
                ];

                foreach ($categories as $category) {
                    if ($category->transaction_type == $transactionType) {
                        $data[$transactionType]['intervals'][$key]['categories'][$category->id]['sum'] = 0;
                    }
                }
            }
        }

        foreach ($query->batch(100) as $transactions) {
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                $intervalKey = $this->intervalService->getIntervalKey((new DateTime())->setTimestamp($transaction->date));

                if ($intervalKey !== null) {
                    $data[$transaction->type]['intervals'][$intervalKey]['categories'][$transaction->category_id]['sum'] += $transaction->sum;
                    $data[$transaction->type]['intervals'][$intervalKey]['sum'] += $transaction->sum;
                    $data[$transaction->type]['sum'] += $transaction->sum;
                }
            }
        }

        return $data;
    }

    /**
     * @param StatsFilterForm $filterForm
     * @param ProductCategory[] $categories
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getProductCategoriesStats(StatsFilterForm $filterForm, array $categories): array
    {
        $dateFrom = (new DateTime($filterForm->from_year . '-' .$filterForm->from_month . '-01'))
            ->modify('first day of this month');
        $dateTo = (new DateTime($filterForm->to_year . '-' . $filterForm->to_month . '-01'))
            ->modify('last day of this month');

        $this->intervalService = new IntervalService($dateFrom, $dateTo, Steps::MONTH);

        $query = Transaction::find()
            ->joinWith(['check', 'check.items', 'check.items.product'])
            ->where(['type' => TransactionTypes::CREDIT])
            ->andWhere(['>=', 'date', $dateFrom->getTimestamp()])
            ->andWhere(['<=', 'date', $dateTo->getTimestamp()])
            ->andWhere([Transaction::tableName() . '.user_id' => App::$app->user->id]);

        $data = [
            'sum' => 0,
            'intervals' => [],
        ];

        foreach ($this->getIntervals() as $key => $interval) {
            $data['intervals'][$key] = [
                'sum' => 0,
                'categories' => [],
            ];

            foreach ($categories as $category) {
                $data['intervals'][$key]['categories'][$category->id] = [
                    'sum' => 0,
                ];
            }
        }

        foreach ($query->batch(100) as $transactions) {
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                $intervalKey = $this->intervalService->getIntervalKey((new DateTime())->setTimestamp($transaction->date));

                if ($intervalKey !== null && $transaction->check !== null) {
                    foreach ($transaction->check->items as $item) {
                        $categoryId = $item->product->category_id;
                        $sum = $item->sum;

                        $data['intervals'][$intervalKey]['categories'][$categoryId]['sum'] += $sum;
                        $data['intervals'][$intervalKey]['sum'] += $sum;
                        $data['sum'] += $sum;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param StatsFilterForm $filterForm
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getProductBySumStats(StatsFilterForm $filterForm): array
    {
        $data = $this->getProductStats($filterForm);

        foreach ($data['intervals'] as $intervalKey => &$value) {
            uasort($value['products'], function($a, $b) {
                return $a['sum'] < $b['sum'];
            });

            $i = 1;

            foreach ($value['products'] as $productId => $product) {
                if ($i > 10) {
                    unset($value['products'][$productId]);
                }

                $i++;
            }
        }

        return $data;
    }


    /**
     * @param StatsFilterForm $filterForm
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getProductStats(StatsFilterForm $filterForm): array
    {
        $dateFrom = (new DateTime($filterForm->from_year . '-' .$filterForm->from_month . '-01'))
            ->modify('first day of this month');
        $dateTo = (new DateTime($filterForm->to_year . '-' . $filterForm->to_month . '-01'))
            ->modify('last day of this month');

        $this->intervalService = new IntervalService($dateFrom, $dateTo, Steps::MONTH);

        $query = Transaction::find()
            ->joinWith(['check', 'check.items', 'check.items.product'])
            ->where(['type' => TransactionTypes::CREDIT])
            ->andWhere(['>=', 'date', $dateFrom->getTimestamp()])
            ->andWhere(['<=', 'date', $dateTo->getTimestamp()])
            ->andWhere([Transaction::tableName() . '.user_id' => App::$app->user->id]);

        $data = [
            'intervals' => [],
        ];

        foreach ($this->getIntervals() as $key => $interval) {
            $data['intervals'][$key] = [
                'products' => [],
            ];
        }

        foreach ($query->batch(100) as $transactions) {
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                $intervalKey = $this->intervalService->getIntervalKey((new DateTime())->setTimestamp($transaction->date));

                if ($intervalKey !== null && $transaction->check !== null) {
                    foreach ($transaction->check->items as $item) {
                        $productId = $item->product->id;
                        $sum = $item->sum;
                        $quantity = $item->quantity;

                        if (!isset($data['intervals'][$intervalKey]['products'][$productId])) {
                            $data['intervals'][$intervalKey]['products'][$productId] = [
                                'name' => $item->product->name,
                                'sum' => 0,
                                'quantity' => 0,
                            ];
                        }

                        $data['intervals'][$intervalKey]['products'][$productId]['sum'] += $sum;
                        $data['intervals'][$intervalKey]['products'][$productId]['quantity'] += $quantity;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getIntervals() : array
    {
        return $this->intervalService->getIntervals();
    }
}