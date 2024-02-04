<?php

namespace common\components\helpers;

use Yii;

class MessagesHelper
{
    public const SUCCESS_CATEGORY = 'success';
    public const ERROR_CATEGORY = 'error';

    /**
     * Add success message to session
     * @param string $message
     * @return void
     */
    public static function addSuccessMessage(string $message): void
    {
        self::addMessage(self::SUCCESS_CATEGORY, $message);
    }

    /**
     * Add error message to session
     * @param string $message
     * @return void
     */
    public static function addErrorMessage(string $message): void
    {
        self::addMessage(self::ERROR_CATEGORY, $message);
    }

    /**
     * Add message to session
     * @param string $category
     * @param string $message
     * @return void
     */
    protected static function addMessage(string $category, string $message): void
    {
        Yii::$app->session->setFlash($category, $message);
    }
}
