<?php

namespace frontend\modules\users;

use Yii;
use yii\base\Module as BaseModule;
use yii\i18n\PhpMessageSource;

/**
 * Class Module
 * @package frontend\modules\users
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'users\controllers';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    /**
     * Register module translation
     */
    public function registerTranslations(): void
    {
        Yii::$app->i18n->translations['users'] = [
            'class' => PhpMessageSource::class,
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'users' => 'users.php',
            ],
        ];
    }
}
