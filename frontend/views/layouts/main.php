<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => [
                [
                    'label' => Yii::t('yii', 'Home'),
                    'url' => ['/site/index'],
                ],
                [
                    'label' => Yii::t('app', 'Purchases'),
                    'url' => ['/purchases/default/index'],
                ],
            ],
        ]);

        echo Html::beginTag('div', ['class' => ['d-flex']]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => [
                [
                    'label' => Yii::t('app', 'My purchases'),
                    'url' => ['/purchases/customer-purchase/index'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' => Yii::t('app', 'Login'),
                    'url' => ['/users/default/login'],
                    'visible' => Yii::$app->user->isGuest,
                ],
                [
                    'label' => Yii::t('app', 'Signup'),
                    'url' => ['/users/default/signup'],
                    'visible' => Yii::$app->user->isGuest,
                ],
                [
                    'label' => Yii::t('app', 'Logout'),
                    'url' => ['/users/default/logout'],
                    'visible' => !Yii::$app->user->isGuest,
                    'linkOptions' => [
                        'data-method' => 'post',
                    ]
                ],
            ],
        ]);
        echo Html::endTag('div');

        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-end"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
