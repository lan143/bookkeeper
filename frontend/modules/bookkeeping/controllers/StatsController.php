<?php

namespace frontend\modules\bookkeeping\controllers;

use bulldozer\App;
use bulldozer\web\Controller;
use common\ar\ProductCategory;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\ar\Transaction;
use frontend\modules\bookkeeping\forms\StatsFilterForm;
use frontend\modules\bookkeeping\services\StatsService;
use yii\helpers\ArrayHelper;

/**
 * Class StatsController
 * @package frontend\modules\api\controllers
 */
class StatsController extends Controller
{
    /**
     * @var StatsService
     */
    private $statsService;

    /**
     * StatsController constructor.
     * @param string $id
     * @param $module
     * @param StatsService $statsService
     * @param array $config
     */
    public function __construct(string $id, $module, StatsService $statsService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->statsService = $statsService;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): string
    {
        /** @var StatsFilterForm $statsFilterForm */
        $statsFilterForm = App::createObject(StatsFilterForm::class);

        $statsFilterForm->load(App::$app->request->get());
        $statsFilterForm->validate();

        $categories = Category::findWithUser()->all();

        $data = $this->statsService->getDebitCreditStats($statsFilterForm, $categories);

        return $this->render('index', [
            'data' => $data,
            'intervals' => $this->statsService->getIntervals(),
        ]);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCategories(): string
    {
        /** @var StatsFilterForm $statsFilterForm */
        $statsFilterForm = App::createObject(StatsFilterForm::class);

        $statsFilterForm->load(App::$app->request->get());
        $statsFilterForm->validate();

        $categories = Category::findWithUser()->all();

        $debitCategories = $creditCategories = [];

        foreach ($categories as $category) {
            if ($category->transaction_type == TransactionTypes::CREDIT) {
                $creditCategories[$category->id] = $category->name;
            } elseif ($category->transaction_type == TransactionTypes::DEBIT) {
                $debitCategories[$category->id] = $category->name;
            }
        }

        $data = $this->statsService->getDebitCreditStats($statsFilterForm, $categories);

        return $this->render('categories', [
            'data' => $data,
            'intervals' => $this->statsService->getIntervals(),
            'creditCategories' => $creditCategories,
            'debitCategories' => $debitCategories,
        ]);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCheckCategories()
    {
        /** @var StatsFilterForm $statsFilterForm */
        $statsFilterForm = App::createObject(StatsFilterForm::class);

        $statsFilterForm->load(App::$app->request->get());
        $statsFilterForm->validate();

        $categories = ProductCategory::find()->all();

        $data = $this->statsService->getProductCategoriesStats($statsFilterForm, $categories);

        return $this->render('check-categories', [
            'data' => $data,
            'intervals' => $this->statsService->getIntervals(),
            'categories' => $categories,
        ]);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCheckProducts()
    {
        /** @var StatsFilterForm $statsFilterForm */
        $statsFilterForm = App::createObject(StatsFilterForm::class);

        $statsFilterForm->load(App::$app->request->get());
        $statsFilterForm->validate();

        $data = $this->statsService->getProductBySumStats($statsFilterForm);

        $dataSets = [];

        foreach ($data['intervals'] as $intervalKey => $intervalData) {
            $dataSets[$intervalKey][] = [
                'name' => 'Товары',
                'colorByPoint' => true,
                'data' => [],
            ];

            foreach ($intervalData['products'] as $product) {
                $dataSets[$intervalKey][0]['data'][] = [
                    'name' => $product['name'],
                    'y' => $product['sum'],
                ];
            }
        }

        return $this->render('check-products', [
            'intervals' => $this->statsService->getIntervals(),
            'dataSets' => $dataSets,
        ]);
    }
}