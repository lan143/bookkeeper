<?php

namespace frontend\modules\bookkeeping;

use bulldozer\base\FrontendModule;
use frontend\modules\bookkeeping\services\CategoryService;
use yii\filters\AccessControl;

/**
 * Class Module
 * @package frontend\modules\bookkeeping
 */
class Module extends FrontendModule
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Module constructor.
     * @param string $id
     * @param Module|null $parent
     * @param CategoryService $categoryService
     * @param array $config
     */
    public function __construct(string $id, $parent = null, CategoryService $categoryService, array $config = [])
    {
        parent::__construct($id, $parent, $config);

        $this->categoryService = $categoryService;
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);

        if ($result) {
            $this->categoryService->createDefaultCategories();
        }

        return $result;
    }
}