<?php

namespace backend\modules\products\services;

use backend\modules\products\forms\ProductForm;
use bulldozer\App;
use common\ar\Product;

/**
 * Class ProductsService
 * @package backend\modules\products\services
 */
class ProductsService
{
    /**
     * @param Product|null $product
     * @return ProductForm
     * @throws \yii\base\InvalidConfigException
     */
    public function getForm(?Product $product = null): ProductForm
    {
        /** @var ProductForm $form */
        $form = App::createObject([
            'class' => ProductForm::class,
        ]);

        if ($product) {
            $form->setAttributes($product->getAttributes($form->getSavedAttributes()));
        }

        return $form;
    }

    /**
     * @param ProductForm $form
     * @param Product|null $product
     * @throws \yii\base\InvalidConfigException
     */
    public function save(ProductForm $form, ?Product $product = null): void
    {
        if ($product === null) {
            $product = App::createObject([
                'class' => Product::class,
            ]);
        }

        $product->setAttributes($form->getAttributes($form->getSavedAttributes()));
        $product->save();
    }
}