<?php

namespace users\models\forms;

use common\components\helpers\ParamsHelper;
use users\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Signup form
 *
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $password
 */
class SignupForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname'], 'trim'],
            [['name', 'surname'], 'required'],
            [['name', 'surname'], 'string', 'min' => 2, 'max' => 100],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message' => Yii::t('users', 'This email address has already been taken.'),
            ],

            ['password', 'required'],
            ['password', 'string', 'min' => ParamsHelper::getParam('user.passwordMinLength')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $user = new User();
        return [
            'name' => $user->getAttributeLabel('name'),
            'surname' => $user->getAttributeLabel('surname'),
            'email' => $user->getAttributeLabel('email'),
            'password' => $user->getAttributeLabel('password_hash'),
        ];
    }

    /**
     * Signs user up.
     * @return bool whether the creating new account was successful and email was sent
     * @throws Exception
     * @throws \Exception
     */
    public function signup(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     * @throws \Exception
     */
    protected function sendEmail(User $user): bool
    {
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
