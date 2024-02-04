<?php

namespace users\models\forms;

use common\components\helpers\ParamsHelper;
use users\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Password reset request form
 *
 * @property string $email
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('users', 'There is no user with this email address.'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => (new User())->getAttributeLabel('email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     * @return bool whether the email was send
     * @throws Exception
     * @throws \Exception
     */
    public function sendEmail(): bool
    {
        /* @var $user User */
        $user = User::find()
            ->active()
            ->byEmail($this->email)
            ->one();

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                [
                    'html' => '/../mail/passwordResetToken-html',
                    'text' => '/../mail/passwordResetToken-text',
                ],
                ['user' => $user]
            )
            ->setFrom(ParamsHelper::getParam('supportEmail'))
            ->setTo($this->email)
            ->setSubject(Yii::t('users', 'Password reset for {APP_NAME}', [
                'APP_NAME' => Yii::$app->name,
            ]))
            ->send();
    }
}
