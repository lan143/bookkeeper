<?php

namespace frontend\modules\api\controllers;

use bulldozer\App;
use DateTime;
use frontend\modules\api\services\CheckService;
use yii\web\BadRequestHttpException;

/**
 * Class CheckController
 * @package frontend\modules\api\controllers
 */
class CheckController extends BaseController
{
    /**
     * @var CheckService
     */
    private $checkService;

    /**
     * CheckController constructor.
     * @param string $id
     * @param $module
     * @param CheckService $checkService
     * @param array $config
     */
    public function __construct(string $id, $module, CheckService $checkService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->checkService = $checkService;
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionParse()
    {
        $inputStr = App::$app->request->post('data');

        $decodedData = [];

        foreach (explode('&', $inputStr) as $chunk) {
            $param = explode("=", $chunk);

            if ($param) {
                $decodedData[urldecode($param[0])] = urldecode($param[1]);
            }
        }

        if (!isset($decodedData['t'])
            || !isset($decodedData['s'])
            || !isset($decodedData['fn'])
            || !isset($decodedData['i'])
            || !isset($decodedData['fp'])
            || !isset($decodedData['n'])
        ) {
            throw new BadRequestHttpException();
        }

        $check = $this->checkService->findOrCreate(
            $decodedData['t'],
            $decodedData['s'],
            $decodedData['fn'],
            $decodedData['i'],
            $decodedData['fp'],
            $decodedData['n']
        );

        return [
            'id' => $check->id,
            'date' => (new DateTime())->setTimestamp($check->t)->format('d.m.Y H:i:s'),
            'sum' => $check->s,
            'category_id' => '',
        ];
    }
}