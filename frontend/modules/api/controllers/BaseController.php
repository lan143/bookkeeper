<?php

namespace frontend\modules\api\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Class BaseController
 * @package frontend\modules\api\controllers
 */
class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON
        ];
        $behaviors['contentNegotiator']['formatParam'] = 'format';
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }
}