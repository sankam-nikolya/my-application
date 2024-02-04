<?php

/** @var yii\web\View $this */
/** @var users\models\User $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['users/default/verify-email', 'token' => $user->verification_token]);
?>

<?= Yii::t('users', 'Hello {USER_NAME},', [
    'USER_NAME' => $user->name . ' ' . $user->surname,
]) ?>

<?= Yii::t('users', 'Follow the link below to verify your email: {LINK}', [
    'LINK' => $verifyLink,
]) ?>

