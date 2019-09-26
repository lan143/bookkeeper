<?php

namespace backend\modules\products\forms;

use bulldozer\base\Form;
use common\ar\Category;

/**
 * Class ProductForm
 * @package backend\modules\products\forms
 */
class ProductForm extends Form
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $category_id;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],

            ['category_id', 'required'],
            ['category_id', 'in', 'range' => Category::find()->asArray()->select(['id'])->column()],
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'name',
            'category_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'category_id' => 'Категория',
        ];
    }
}