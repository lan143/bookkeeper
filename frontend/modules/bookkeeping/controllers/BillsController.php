<?php

namespace frontend\modules\bookkeeping\controllers;

use bulldozer\App;
use bulldozer\web\Controller;
use common\ar\Currency;
use common\ar\Transaction;
use common\enums\BillTypes;
use frontend\modules\bookkeeping\ar\Bill;
use frontend\modules\bookkeeping\services\BillService;
use frontend\modules\bookkeeping\services\CreditService;
use HttpInvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class BillsController
 * @package frontend\modules\bookkeeping\controllers
 */
class BillsController extends Controller
{
    /**
     * @var BillService
     */
    private $billService;

    /**
     * @var CreditService
     */
    private $creditService;

    /**
     * BillsController constructor.
     * @param string $id
     * @param $module
     * @param BillService $billService
     * @param CreditService $creditService
     * @param array $config
     */
    public function __construct(string $id, $module, BillService $billService, CreditService $creditService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->billService = $billService;
        $this->creditService = $creditService;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $bills = Bill::findWithUser()
            ->andWhere(['type' => [BillTypes::CASH, BillTypes::CARD, BillTypes::CREDIT_CARD]])
            ->orderBy(['sum' => SORT_DESC])
            ->all();

        return $this->render('index-bills', [
            'bills' => $bills,
        ]);
    }

    /**
     * @return string
     */
    public function actionContributions()
    {
        $bills = Bill::findWithUser()
            ->andWhere(['type' => [BillTypes::CONTRIBUTION]])
            ->orderBy(['sum' => SORT_DESC])
            ->all();

        return $this->render('index-contributions', [
            'bills' => $bills,
        ]);
    }

    /**
     * @return string
     */
    public function actionCredits()
    {
        $bills = Bill::findWithUser()
            ->andWhere(['type' => [BillTypes::CREDIT]])
            ->orderBy(['sum' => SORT_DESC])
            ->all();

        return $this->render('index-credits', [
            'bills' => $bills,
        ]);
    }

    /**
     * @param int $type
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws HttpInvalidParamException
     * @throws \yii\base\Exception
     */
    public function actionCreate(int $type)
    {
        if (!in_array($type, array_keys(BillTypes::listData()))) {
            throw new HttpInvalidParamException('Неверный тип');
        }

        $billForm = $this->billService->getForm($type);

        if ($billForm->load(App::$app->request->post()) && $billForm->validate()) {
            $this->billService->saveBill($type, $billForm);

            if ($type == BillTypes::CREDIT) {
                return $this->redirect(['bills/credits']);
            } else if ($type == BillTypes::CONTRIBUTION) {
                return $this->redirect(['bills/contributions']);
            } else {
                return $this->redirect(['bills/index']);
            }
        }

        $currencies = ArrayHelper::map(Currency::find()->all(), 'id', 'name');

        return $this->render('create', [
            'type' => $type,
            'billForm' => $billForm,
            'currencies' => $currencies,
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate(int $id)
    {
        $bill = Bill::findWithUser()->andWhere(['id' => $id])->one();

        if ($bill === null) {
            throw new NotFoundHttpException();
        }

        $billForm = $this->billService->getForm($bill->type, $bill);

        if ($billForm->load(App::$app->request->post()) && $billForm->validate()) {
            $this->billService->saveBill($bill->type, $billForm, $bill);

            if ($bill->type == BillTypes::CREDIT) {
                return $this->redirect(['bills/credits']);
            } else if ($bill->type == BillTypes::CONTRIBUTION) {
                return $this->redirect(['bills/contributions']);
            } else {
                return $this->redirect(['bills/index']);
            }
        }

        $currencies = ArrayHelper::map(Currency::find()->all(), 'id', 'name');

        return $this->render('update', [
            'billForm' => $billForm,
            'currencies' => $currencies,
            'bill' => $bill,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView(int $id)
    {
        $bill = Bill::findWithUser()->andWhere(['id' => $id])->one();

        if ($bill === null) {
            throw new NotFoundHttpException();
        }

        if ($bill->type == BillTypes::CREDIT) {
            $scheduleOfPayments = $this->creditService->buildScheduleOfPaymentsCompact($bill);
            //$scheduleOfPayments = $this->creditService->buildScheduleOfPayments($bill);
            $stats = $this->creditService->getCreditStats($bill);

            return $this->render('view-credit', [
                'bill' => $bill,
                'scheduleOfPayments' => $scheduleOfPayments,
                'stats' => $stats,
            ]);
        } else {
            $transactionsDataProvider = App::createObject([
                'class' => ActiveDataProvider::class,
                'query' => Transaction::find()
                    ->orWhere(['source_bill_id' => $bill->id])
                    ->orWhere(['destination_bill_id' => $bill->id])
                    ->orderBy(['date' => SORT_DESC]),
            ]);

            return $this->render('view', [
                'bill' => $bill,
                'transactionsDataProvider' => $transactionsDataProvider,
            ]);
        }
    }
}
