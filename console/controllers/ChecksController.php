<?php

namespace console\controllers;

use bulldozer\App;
use common\ar\Check;
use common\enums\CheckStatuses;
use console\services\CheckService;
use console\tasks\GetCheckInfoJob;
use yii\console\Controller;
use yii\queue\Queue;

/**
 * Class ChecksController
 * @package console\controllers
 */
class ChecksController extends Controller
{
    /**
     * @var CheckService
     */
    private $checkService;

    /**
     * ChecksController constructor.
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
     * @param int|null $id
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionUpdateInfo(?int $id = null)
    {
        $query = Check::find();

        if ($id !== null) {
            $query->andWhere(['id' => $id]);
        } else {
            $query->andWhere(['status' => [CheckStatuses::NEW, CheckStatuses::ERROR]]);
        }

        $checks = $query->all();

        $i = 1;

        foreach ($checks as $check) {
            echo 'Process ' . $i . '/' . count($checks) . PHP_EOL;
            $this->checkService->updateCheckData($check);
            $i++;
        }
    }

    /**
     * @param int|null $id
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateAddInfo(?int $id = null)
    {
        $query = Check::find();

        if ($id !== null) {
            $query->andWhere(['id' => $id]);
        } else {
            $query->andWhere(['update_categories_status' => [CheckStatuses::NEW, CheckStatuses::ERROR]]);
        }

        $checks = $query->all();

        $i = 1;

        foreach ($checks as $check) {
            echo 'Process ' . $i . '/' . count($checks) . PHP_EOL;
            $this->checkService->updateProductCategories($check);
            $i++;
        }
    }
}