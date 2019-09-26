<?php

namespace frontend\modules\bookkeeping\controllers;

use bulldozer\App;
use bulldozer\web\Controller;
use common\ar\ProductCategory;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Bill;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\ar\Transaction;
use frontend\modules\bookkeeping\services\TransactionService;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class TransactionsController
 * @package frontend\modules\bookkeeping\controllers
 */
class TransactionsController extends Controller
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * TransactionsController constructor.
     * @param string $id
     * @param $module
     * @param TransactionService $transactionService
     * @param array $config
     */
    public function __construct(string $id, $module, TransactionService $transactionService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->transactionService = $transactionService;
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'scan') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @param int $billId
     * @param int $type
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate(int $billId, int $type)
    {
        if (!in_array($type, array_keys(TransactionTypes::listData()))) {
            throw new Exception('Incorrect type');
        }

        $bill = Bill::findWithUser()->andWhere(['id' => $billId])->one();

        if ($bill === null) {
            throw new NotFoundHttpException();
        }

        $transactionForm = $this->transactionService->getForm($type);
        $transactionForm->setBill($bill);
        $transactionForm->setType($type);

        if ($transactionForm->load(App::$app->request->post()) && $transactionForm->validate()) {
            $this->transactionService->save($type, $transactionForm, $bill);

            return $this->redirect(['bills/view', 'id' => $bill->id]);
        }

        $categories = ArrayHelper::map(Category::findWithUser()
            ->andWhere(['transaction_type' => $type])
            ->all(), 'id', 'name');

        return $this->render('create', [
            'type' => $type,
            'transactionForm' => $transactionForm,
            'categories' => $categories,
            'bill' => $bill,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $transaction = Transaction::find()->andWhere(['id' => $id])->one();

        if ($transaction === null) {
            throw new NotFoundHttpException();
        }

        $categoriesData = [];
        $categories = [];
        $dataSets = [];

        if ($transaction->check) {
            $dataSets[] = [
                'name' => 'Категории',
                'colorByPoint' => true,
                'data' => [],
            ];

            foreach ($transaction->check->items as $item) {
                if ($item->product->category_id > 0) {
                    if (!isset($categoriesData[$item->product->category_id])) {
                        $categoriesData[$item->product->category_id] = 0;
                    }

                    $categoriesData[$item->product->category_id] += $item->sum;
                }
            }

            if (count($categoriesData) > 0) {
                $categories = ArrayHelper::map(
                    ProductCategory::find()
                        ->where(['id' => array_keys($categoriesData)])
                        ->all(),
                    'id',
                    'name'
                );
            }

            foreach ($categoriesData as $categoryId => $sum) {
                $dataSets[0]['data'][] = [
                    'name' => $categories[$categoryId],
                    'y' => $sum,
                ];
            }
        }

        return $this->render('view', [
            'transaction' => $transaction,
            'dataSets' => $dataSets,
        ]);
    }

    /**
     * @param int $billId
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionScan(int $billId)
    {
        $bill = Bill::findWithUser()->andWhere(['id' => $billId])->one();

        if ($bill === null) {
            throw new NotFoundHttpException();
        }

        $transactionForm = $this->transactionService->getForm(TransactionTypes::CREDIT);
        $transactionForm->setBill($bill);
        $transactionForm->setType(TransactionTypes::CREDIT);

        if ($transactionForm->load(App::$app->request->post()) && $transactionForm->validate()) {
            $this->transactionService->save(TransactionTypes::CREDIT, $transactionForm, $bill);

            return $this->redirect(['bills/view', 'id' => $bill->id]);
        }

        $categories = ArrayHelper::map(Category::findWithUser()
            ->andWhere(['transaction_type' => TransactionTypes::CREDIT])
            ->all(), 'id', 'name');

        return $this->render('scan', [
            'bill' => $bill,
            'transactionForm' => $transactionForm,
            'categories' => $categories,
        ]);
    }
}