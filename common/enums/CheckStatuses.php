<?php

namespace common\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class CheckStatuses
 * @package common\enums
 */
class CheckStatuses extends BaseEnum
{
    const NEW = 1;
    const COMPLETE = 2;
    const ERROR = 3;
    const NOT_FOUND = 4;
}