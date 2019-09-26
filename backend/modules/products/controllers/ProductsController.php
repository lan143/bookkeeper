<?php

namespace backend\modules\products\controllers;

use backend\modules\products\forms\ProductSearch;
use backend\modules\products\services\ProductsService;
use bulldozer\App;
use bulldozer\web\Controller;
use common\ar\Product;
use common\ar\ProductCategory;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ProductsController extends Controller
{
    /**
     * @var ProductsService
     */
    private $productsService;

    /**
     * ProductsController constructor.
     * @param string $id
     * @param $module
     * @param ProductsService $productsService
     * @param array $config
     */
    public function __construct(string $id, $module, ProductsService $productsService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->productsService = $productsService;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): string
    {
        /** @var ProductSearch $searchModel */
        $searchModel = App::createObject([
            'class' => ProductSearch::class,
        ]);
        $dataProvider = $searchModel->search(App::$app->request->queryParams);

        $categories = ArrayHelper::map(ProductCategory::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'categories' => $categories,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $productForm = $this->productsService->getForm();

        if ($productForm->load(App::$app->request->post()) && $productForm->validate()) {
            $this->productsService->save($productForm);

            return $this->redirect(['index']);
        }

        $categories = ArrayHelper::map(ProductCategory::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');

        return $this->render('create', [
            'productForm' => $productForm,
            'categories' => $categories,
        ]);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate(int $id)
    {
        $product = Product::findOne($id);

        if ($product === null) {
            throw new NotFoundHttpException();
        }

        $productForm = $this->productsService->getForm($product);

        if ($productForm->load(App::$app->request->post()) && $productForm->validate()) {
            $this->productsService->save($productForm, $product);

            return $this->redirect(['index']);
        }

        $categories = ArrayHelper::map(ProductCategory::find()->all(), 'id', 'name');

        return $this->render('update', [
            'productForm' => $productForm,
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $product = Product::findOne($id);

        if ($product === null) {
            throw new NotFoundHttpException();
        }

        $product->delete();

        return $this->redirect(['index']);
    }
}