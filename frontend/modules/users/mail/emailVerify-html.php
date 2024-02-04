<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var users\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['users/default/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>
        <?= Yii::t('users', 'Hello {USER_NAME},', [
            'USER_NAME' => Html::encode($user->name) . ' emailVerify-html.php' . Html::encode($user->surname),
        ]) ?>
    </p>

    <p>
        <?= Yii::t('users', 'Follow the link below to verify your email: {LINK}', [
            'LINK' => Html::a(Html::encode($verifyLink), $verifyLink),
        ]) ?>
    </p>
</div>
