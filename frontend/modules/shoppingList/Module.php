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
}