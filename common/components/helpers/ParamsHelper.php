<?php

namespace common\components\helpers;

use Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ParamsHelper
 * @package common\components\helpers
 */
class ParamsHelper
{
    /**
     * Return param value
     * @param string $param
     * @param mixed $default
     * @return mixed|null
     * @throws Exception
     */
    public static function getParam(string $param, $default = null)
    {
        return ArrayHelper::getValue(Yii::$app->params ?? [], $param, $default);
    }

    /**
     * Return param value
     * @param string $paramName
     * @param null $default
     * @return string|array|null
     * @throws Exception
     */
    public static function getViewParam(string $paramName, $default = null)
    {
        return ArrayHelper::getValue(Yii::$app->view->params, $paramName, $default);
    }
}
