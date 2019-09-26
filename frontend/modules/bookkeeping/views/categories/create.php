<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\CategoryForm $categoryForm
 */

$this->title = 'Новая категория';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Новая категория';

echo $this->render('_form', ['categoryForm' => $categoryForm, 'isNewRecord' => true]);