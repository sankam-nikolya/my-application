<?php

namespace frontend\modules\purchases;

use Yii;
use yii\base\Module as BaseModule;
use yii\i18n\PhpMessageSource;

/**
 * Class Module
 * @package frontend\modules\purchases
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'purchases\controllers';

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
        Yii::$app->i18n->translations['purchases'] = [
            'class' => PhpMessageSource::class,
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'purchases' => 'purchases.php',
            ],
        ];
    }
}
