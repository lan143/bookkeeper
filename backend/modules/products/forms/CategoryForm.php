<?php

namespace backend\modules\products\forms;

use bulldozer\base\Form;

/**
 * Class CategoryForm
 * @package backend\modules\products\forms
 */
class CategoryForm extends Form
{
    /**
     * @var string
     */
    public $name;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'name',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}