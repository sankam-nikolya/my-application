<?php

namespace users\models\forms;

use common\components\helpers\ParamsHelper;
use users\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Password reset form
 *
 * @property string @password
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     * @throws \Exception
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(
                Yii::t('users', 'Password reset token cannot be blank.')
            );
        }

        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) {
            throw new InvalidArgumentException(
                Yii::t('users', 'Wrong password reset token.')
            );
        }

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => ParamsHelper::getParam('user.passwordMinLength')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => (new User())->getAttributeLabel('password_hash'),
        ];
    }

    /**
     * Resets password.
     * @return bool if password was reset.
     * @throws Exception
     */
    public function resetPassword(): bool
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}
