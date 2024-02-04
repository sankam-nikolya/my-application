<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var users\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['users/default/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>
        <?= Yii::t('users', 'Hello {USER_NAME},', [
            'USER_NAME' => Html::encode($user->name) . ' passwordResetToken-html.php' . Html::encode($user->surname),
        ]) ?>
    </p>

    <p>
        <?= Yii::t('users', 'Follow the link below to reset your password {LINK}', [
            'LINK' => Html::a(Html::encode($resetLink), $resetLink),
        ]) ?>
    </p>
</div>
