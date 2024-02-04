<?php

namespace users\models\forms;

use common\components\helpers\ParamsHelper;
use users\models\User;
use Yii;
use yii\base\Model;

/**
 * Resend verification email form
 *
 * @property string $email
 */
class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
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
                'filter' => ['status' => User::STATUS_INACTIVE],
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
     * Sends confirmation email to user
     * @return bool whether the email was sent
     * @throws \Exception
     */
    public function sendEmail(): bool
    {
        /** @var User $user */
        $user = User::find()
            ->inActive()
            ->byEmail($this->email)
            ->one();

        if ($user === null) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                [
                    'html' => '/../mail/emailVerify-html',
                    'text' => '/../mail/emailVerify-text',
                ],
                ['user' => $user]
            )
            ->setFrom(ParamsHelper::getParam('supportEmail'))
            ->setTo($this->email)
            ->setSubject(Yii::t('users', 'Account registration at {APP_NAME}', [
                'APP_NAME' => Yii::$app->name,
            ]))
            ->send();
    }
}
