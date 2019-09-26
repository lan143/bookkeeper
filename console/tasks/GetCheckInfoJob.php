<?php

namespace console\tasks;

use bulldozer\App;
use common\ar\Check;
use console\services\CheckService;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class GetCheckInfoJob
 * @package console\tasks
 */
class GetCheckInfoJob extends BaseObject implements JobInterface
{
    /**
     * @var int
     */
    public $check_id;

    /**
     * @var CheckService
     */
    private $checkService;

    /**
     * GetCheckInfoJob constructor.
     * @param CheckService $checkService
     * @param array $config
     */
    public function __construct(CheckService $checkService, array $config = [])
    {
        parent::__construct($config);

        $this->checkService = $checkService;
    }

    /**
     * @param Queue $queue which pushed and is handling the job
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function execute($queue)
    {
        $check = Check::findOne($this->check_id);

        if ($check) {
            if (!$this->checkService->updateCheckData($check)) {
                $queue->delay(60 * 60)->push(App::createObject([
                    'class' => GetCheckInfoJob::class,
                    'check_id' => $this->check_id,
                ]));
            } else {
                $queue->push(App::createObject([
                    'class' => UpdateProductsCategoriesJob::class,
                    'check_id' => $this->check_id,
                ]));
            }
        }
    }
}