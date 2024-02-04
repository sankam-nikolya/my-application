<?php

namespace users\models\forms;

use users\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 *
 * @property string $email
 * @property string $password
 * @property bool $rememberMe
 * @property-read \users\models\User|null $user
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $user = new User();
        return [
            'email' => $user->getAttributeLabel('email'),
            'password' => $user->getAttributeLabel('password_hash'),
            'rememberMe' => Yii::t('users', 'Remember me'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError(
                    $attribute,
                    Yii::t('users', 'Incorrect email or password.')
                );
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::find()
                ->active()
                ->byEmail($this->email)
                ->one();
        }

        return $this->_user;
    }
}
