<?php

namespace backend\modules\products\controllers;

use backend\modules\products\services\CategoriesService;
use bulldozer\App;
use bulldozer\web\Controller;
use common\ar\ProductCategory;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class CategoriesController
 * @package backend\modules\products\controllers
 */
class CategoriesController extends Controller
{
    /**
     * @var CategoriesService
     */
    private $categoriesService;

    /**
     * CategoriesController constructor.
     * @param string $id
     * @param $module
     * @param CategoriesService $categoriesService
     * @param array $config
     */
    public function __construct(string $id, $module, CategoriesService $categoriesService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->categoriesService = $categoriesService;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): string
    {
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => ProductCategory::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $categoryForm = $this->categoriesService->getForm();

        if ($categoryForm->load(App::$app->request->post()) && $categoryForm->validate()) {
            $this->categoriesService->save($categoryForm);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'categoryForm' => $categoryForm,
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
        $category = ProductCategory::findOne($id);

        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $categoryForm = $this->categoriesService->getForm($category);

        if ($categoryForm->load(App::$app->request->post()) && $categoryForm->validate()) {
            $this->categoriesService->save($categoryForm, $category);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'categoryForm' => $categoryForm,
            'category' => $category,
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
        $category = ProductCategory::findOne($id);

        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $category->delete();

        return $this->redirect(['index']);
    }
}