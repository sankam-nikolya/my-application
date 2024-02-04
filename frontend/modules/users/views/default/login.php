<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var users\models\forms\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('users', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('users', 'Please fill out the following fields to login:') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email')->input('email', ['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="my-1 mx-0 text-grey" style="color:#999;">
                <p>
                    <?= Yii::t('users', 'If you forgot your password you can {ACTION_LINK}.', [
                        'ACTION_LINK' => Html::a(
                            Yii::t('users', 'reset it'),
                            ['request-password-reset']
                        )
                    ]) ?>
                </p>
                <p>
                    <?= Yii::t('users', 'Need new verification email? {ACTION_LINK}', [
                        'ACTION_LINK' => Html::a(
                            Yii::t('users', 'Resend'),
                            ['resend-verification-email']
                        )
                    ]) ?>
                </p>
            </div>

            <div class="form-group">
                <?= Html::submitButton(
                    Yii::t('users', 'Login'),
                    ['class' => 'btn btn-primary', 'name' => 'login-button']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
