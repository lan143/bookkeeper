<?php

namespace backend\modules\products;

use bulldozer\App;
use bulldozer\base\BackendModule;

/**
 * Class Module
 * @package backend\modules\products
 */
class Module extends BackendModule
{
    /**
     * @var string
     */
    public $defaultRoute = 'products';

    /**
     * @inheritdoc
     */
    public function getMenuItems(): array
    {
        $moduleId = isset(App::$app->controller->module) ? App::$app->controller->module->id : '';
        $controllerId = App::$app->controller->id;

        return [
            [
                'label' => 'Товары',
                'icon' => 'fa fa-product-hunt',
                'child' => [
                    [
                        'label' => 'Список',
                        'icon' => 'fa fa-list',
                        'url' => ['/products'],
                        'active' => $moduleId == 'products' && $controllerId == 'products',
                    ],
                    [
                        'label' => 'Категории',
                        'icon' => 'fa fa-podcast',
                        'url' => ['/products/categories'],
                        'active' => $moduleId == 'products' && $controllerId == 'categories',
                    ],
                ],
            ]
        ];
    }

    /**
     * @param string $route
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function createController($route)
    {
        $validRoutes = [$this->defaultRoute, 'categories'];
        $isValidRoute = false;

        foreach ($validRoutes as $validRoute) {
            if (strpos($route, $validRoute) === 0) {
                $isValidRoute = true;
                break;
            }
        }

        return (empty($route) or $isValidRoute)
            ? parent::createController($route)
            : parent::createController("{$this->defaultRoute}/{$route}");
    }
}