<?php

namespace frontend\modules\bookkeeping\controllers;

use bulldozer\App;
use bulldozer\web\Controller;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\services\CategoryService;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class CategoriesController
 * @package frontend\modules\bookkeeping\controllers
 */
class CategoriesController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CategoriesController constructor.
     * @param string $id
     * @param $module
     * @param CategoryService $categoryService
     * @param array $config
     */
    public function __construct(string $id, $module, CategoryService $categoryService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->categoryService = $categoryService;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): string
    {
        $dataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => Category::findWithUser(),
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
        $categoryForm = $this->categoryService->getForm();

        if ($categoryForm->load(App::$app->request->post()) && $categoryForm->validate()) {
            $this->categoryService->save($categoryForm);

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
        $category = Category::findWithUser()->andWhere(['id' => $id])->one();

        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $categoryForm = $this->categoryService->getForm($category);

        if ($categoryForm->load(App::$app->request->post()) && $categoryForm->validate()) {
            $this->categoryService->save($categoryForm, $category);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'category' => $category,
            'categoryForm' => $categoryForm,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id)
    {
        $category = Category::findWithUser()->andWhere(['id' => $id])->one();

        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $this->categoryService->delete($category);

        return $this->redirect(['index']);
    }
}