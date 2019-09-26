<?php

namespace backend\widgets;

use bulldozer\App;
use yii\helpers\Url;

class MenuWidget extends \yii\base\Widget
{
    /**
     * @var array
     */
    public $menuItems;

    /**
     * @var string
     */
    public $route;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->route === null && App::$app->controller !== null) {
            $this->route = App::$app->requestedRoute;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $items = [];

        foreach ($this->menuItems as $menuItem) {
            if (isset($menuItem['rules']) && !$this->checkRules($menuItem['rules'])) {
                continue;
            }

            $item = [
                'name' => $menuItem['label'],
                'icon' => $menuItem['icon'],
                'active' => isset($menuItem['active']) ? $menuItem['active'] : $this->isItemActive($menuItem),
            ];

            if (isset($menuItem['url'])) {
                $item['url'] = Url::to($menuItem['url']);
            }

            if (isset($menuItem['child']) && count($menuItem['child']) > 0) {
                $item['child'] = [];

                foreach ($menuItem['child'] as $childItem) {
                    if (isset($childItem['rules']) && !$this->checkRules($childItem['rules'])) {
                        continue;
                    }

                    $active = isset($childItem['active']) ? $childItem['active'] : $this->isItemActive($childItem);

                    $item['child'][] = [
                        'name' => $childItem['label'],
                        'icon' => $childItem['icon'],
                        'url' => Url::to($childItem['url']),
                        'active' => $active,
                    ];

                    if ($active) {
                        $item['active'] = true;
                    }
                }
            }

            if (!isset($item['child']) || (isset($item['child']) && count($item['child']) > 0)) {
                $items[] = $item;
            }
        }

        return $this->render('menu', [
            'items' => $items,
        ]);
    }

    /**
     * @param array $rules
     * @return bool
     */
    protected function checkRules(array $rules) : bool
    {
        foreach ($rules as $rule) {
            if (App::$app->user->can($rule)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item) : bool
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];

            if ($route[0] !== '/' && App::$app->controller) {
                $route = App::$app->controller->module->getUniqueId() . '/' . $route;
            }

            if (ltrim($route, '/') !== $this->route) {
                return false;
            }

            unset($item['url']['#']);

            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);

                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}