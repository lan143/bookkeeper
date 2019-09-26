<?php

namespace frontend\modules\bookkeeping\services;

use bulldozer\App;
use common\ar\DefaultCategory;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\forms\CategoryForm;

/**
 * Class CategoryService
 * @package frontend\modules\bookkeeping\services
 */
class CategoryService
{
    /**
     * @param Category|null $category
     * @return CategoryForm
     * @throws \yii\base\InvalidConfigException
     */
    public function getForm(?Category $category = null): CategoryForm
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
     * @param Category|null $category
     * @throws \yii\base\InvalidConfigException
     */
    public function save(CategoryForm $form, ?Category $category = null): void
    {
        if ($category === null) {
            $category = App::createObject([
                'class' => Category::class,
            ]);
        }

        $category->setAttributes($form->getAttributes($form->getSavedAttributes()));

        $category->save();
    }

    /**
     * @param Category $category
     */
    public function delete(Category $category): void
    {
        $category->is_deleted = 1;
        $category->save();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function createDefaultCategories(): void
    {
        $category = Category::findWithUser()->one();

        if ($category === null) {
            $defaultCategories = DefaultCategory::find()->all();

            foreach ($defaultCategories as $defaultCategory) {
                /** @var Category $category */
                $category = App::createObject([
                    'class' => Category::class,
                    'default_category_id' => $defaultCategory->id,
                    'transaction_type' => $defaultCategory->transaction_type,
                    'name' => $defaultCategory->name,
                ]);
                $category->save();
            }
        }
    }
}