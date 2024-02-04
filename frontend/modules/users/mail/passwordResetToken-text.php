<?php

/** @var yii\web\View $this */
/** @var users\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['users/default/reset-password', 'token' => $user->password_reset_token]);
?>
<?= Yii::t('users', 'Hello {USER_NAME},', [
    'USER_NAME' => $user->name . ' ' . $user->surname,
]) ?>

<?= Yii::t('users', 'Follow the link below to reset your password {LINK}', [
    'LINK' => $resetLink,
]) ?>

