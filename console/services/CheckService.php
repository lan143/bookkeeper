<?php

namespace console\services;

use bulldozer\App;
use common\ar\Check;
use common\ar\CheckItem;
use common\ar\Product;
use common\ar\ProductCategory;
use common\components\QrKassaApi;
use common\components\QrKassaApiException;
use common\components\TaxApi;
use common\components\TaxApiException;
use common\enums\CheckStatuses;
use yii\helpers\ArrayHelper;

/**
 * Class CheckService
 * @package console\services
 */
class CheckService
{
    /**
     * @param Check $check
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function updateCheckData(Check $check): bool
    {
        /** @var TaxApi $taxApi */
        $taxApi = App::$app->get('taxApi');

        $transaction = App::$app->db->beginTransaction();

        try {
            $checkInfo = $taxApi->getCheckInfo((int)$check->fn, (int)$check->i, (int)$check->fp);

            $check->status = CheckStatuses::COMPLETE;
            $check->raw_response = $checkInfo;
            $check->shop_name = ArrayHelper::getValue($checkInfo, 'document.receipt.user');
            $check->shop_address = ArrayHelper::getValue($checkInfo, 'document.receipt.retailPlaceAddress');

            CheckItem::deleteAll(['check_id' => $check->id]);

            foreach ($checkInfo['document']['receipt']['items'] as $item) {
                $product = $this->getProduct(ArrayHelper::getValue($item, 'name'), null);

                /** @var CheckItem $checkItem */
                $checkItem = App::createObject([
                    'class' => CheckItem::class,
                    'check_id' => $check->id,
                    'user_id' => $check->user_id,
                    'name' => ArrayHelper::getValue($item, 'name'),
                    'quantity' => ArrayHelper::getValue($item, 'quantity'),
                    'sum' => ArrayHelper::getValue($item, 'sum') / 100,
                    'price' => ArrayHelper::getValue($item, 'price') / 100,
                    'nds10' => ArrayHelper::getValue($item, 'nds10') / 100,
                    'product_id' => $product->id,
                ]);
                $checkItem->save();
            }
        } catch (TaxApiException $e) {
            App::error($e->getMessage());

            if ($e->status == 202) {
                $check->status = CheckStatuses::NOT_FOUND;
            } else {
                $check->status = CheckStatuses::ERROR;
            }
        }

        $check->save();

        $transaction->commit();

        return $check->status == CheckStatuses::COMPLETE;
    }

    /**
     * @param Check $check
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function updateProductCategories(Check $check): bool
    {
        /** @var QrKassaApi $qrKassaApi */
        $qrKassaApi = App::$app->get('qrKassaApi');

        $addCheckInfo = null;
        $checkInfo = $check->raw_response;

        if (!$checkInfo) {
            return false;
        }

        try {
            $addCheckInfo = $qrKassaApi->getCheckInfo($checkInfo);
        } catch (QrKassaApiException $e) {
            App::error('Cant get additional check info. Error: ' . $e->getMessage());

            $check->update_categories_status = CheckStatuses::ERROR;
            $check->save();

            return false;
        }

        foreach ($check->items as $item) {
            $this->getProduct(ArrayHelper::getValue($item, 'name'), $addCheckInfo);
        }

        $check->update_categories_status = CheckStatuses::COMPLETE;
        $check->save();

        return true;
    }

    /**
     * @param string $name
     * @param array $addCheckInfo
     * @return Product
     * @throws \yii\base\InvalidConfigException
     */
    protected function getProduct(string $name, ?array $addCheckInfo): Product
    {
        $product = Product::findOne(['name' => $name]);

        if ($product === null) {
            /** @var Product $product */
            $product = App::createObject([
                'class' => Product::class,
                'name' => $name,
            ]);
            $product->save();
        }

        if ($addCheckInfo) {
            $this->updateProductCategory($product, $addCheckInfo);
        }

        return $product;
    }

    /**
     * @param Product $product
     * @param array $addCheckInfo
     * @throws \yii\base\InvalidConfigException
     */
    protected function updateProductCategory(Product $product, array $addCheckInfo)
    {
        if (!$product->category) {
            $item = null;

            if ($addCheckInfo !== null) {
                foreach ($addCheckInfo['items'] as $item) {
                    if ($item[0] == $product->name) {
                        break;
                    }
                }
            }

            $category = null;

            if ($item !== null) {
                $category = $this->getProductCategory($item[3]);
            }

            if ($category) {
                $product->category_id = $category->id;
                $product->save();
            }
        }
    }

    /**
     * @param string $name
     * @return ProductCategory
     * @throws \yii\base\InvalidConfigException
     */
    protected function getProductCategory(string $name): ProductCategory
    {
        $category = ProductCategory::findOne(['name' => $name]);

        if ($category === null) {
            /** @var ProductCategory $category */
            $category = App::createObject([
                'class' => ProductCategory::class,
                'name' => $name,
            ]);
            $category->save();
        }

        return $category;
    }
}