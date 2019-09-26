<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\CategoryForm $categoryForm
 * @var \frontend\modules\bookkeeping\ar\Category $category
 */

$this->title = 'Редактирование категории: ' . $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;
$this->params['breadcrumbs'][] = 'Редактирование';

echo $this->render('_form', ['categoryForm' => $categoryForm, 'isNewRecord' => false]);