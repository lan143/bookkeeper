<?php

namespace backend\modules\products\services;

use backend\modules\products\forms\CategoryForm;
use bulldozer\App;
use common\ar\ProductCategory;

/**
 * Class CategoriesService
 * @package backend\modules\products\services
 */
class CategoriesService
{
    /**
     * @param ProductCategory|null $category
     * @return CategoryForm
     * @throws \yii\base\InvalidConfigException
     */
    public function getForm(?ProductCategory $category = null): CategoryForm
    {
        /** @var CategoryForm $form */
        $form = App::createObject([
            'class' => CategoryForm::class,
        ]);

        if ($category) {
            $form->setAttributes($category->getAttributes($form->getSavedAttributes()));
        }

        return $form;
    }

    /**
     * @param CategoryForm $form
     * @param ProductCategory|null $category
     * @throws \yii\base\InvalidConfigException
     */
    public function save(CategoryForm $form, ?ProductCategory $category = null): void
    {
        if ($category === null) {
            $category = App::createObject([
                'class' => ProductCategory::class,
            ]);
        }

        $category->setAttributes($form->getAttributes($form->getSavedAttributes()));
        $category->save();
    }
}