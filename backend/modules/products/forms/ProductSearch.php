<?php

namespace backend\modules\products\forms;

use bulldozer\App;
use bulldozer\base\Form;
use common\ar\Product;
use common\ar\ProductCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ProductSearch
 * @package backend\modules\products\forms
 */
class ProductSearch extends Form
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $category_id;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'string'],

            ['category_id', 'in', 'range' => ProductCategory::find()->asArray()->select(['id'])->column()],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search(array $params)
    {
        $query = Product::find();

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = App::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (strlen($this->name) > 0) {
            $query->andWhere(['LIKE', 'name', $this->name]);
        }

        if ($this->category_id > 0) {
            $query->andWhere(['category_id' => $this->category_id]);
        }

        return $dataProvider;
    }
}