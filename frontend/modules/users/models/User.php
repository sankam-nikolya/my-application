<?php

namespace users\models;

use common\components\helpers\ParamsHelper;
use users\models\queries\UserQuery;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property-read string $authKey
 * @property string $password write-only password
 *
 * @property-read string $fullName
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_key', 'password_hash', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            ['email', 'email'],
            [['name', 'surname', 'email'], 'string', 'max' => 100],
            [
                ['auth_key', 'access_token'],
                'string',
                'max' => 32,
            ],
            [
                ['password_reset_token', 'verification_token'],
                'string',
                'max' => 50,
            ],
            ['password_hash', 'string', 'max' => 255],
            ['email', 'unique'],
            ['auth_key', 'unique'],
            ['access_token', 'unique'],
            ['password_reset_token', 'unique'],
            ['verification_token', 'unique'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'E-mail'),
            'name' => Yii::t('users', 'Name'),
            'surname' => Yii::t('users', 'Surname'),
            'password_hash' => Yii::t('users', 'Password'),
            'status' => Yii::t('users', 'Status'),
            'created_at' => Yii::t('users', 'Created at'),
            'updated_at' => Yii::t('users', 'Updated at'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->byId((int)$id)
            ->active()
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->active()
            ->byAccessToken($token)
            ->one();
    }

    /**
     * Finds user by password reset token
     * @param string $token password reset token
     * @return User|null
     * @throws \Exception
     */
    public static function findByPasswordResetToken(string $token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()
            ->active()
            ->byPasswordResetToken($token)
            ->one();
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return User|null
     */
    public static function findByVerificationToken(string $token): ?User
    {
        return static::find()
            ->inActive()
            ->byVerificationToken($token)
            ->one();
    }

    /**
     * Finds out if password reset token is valid
     * @param null|string $token password reset token
     * @return bool
     * @throws \Exception
     */
    public static function isPasswordResetTokenValid(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = ParamsHelper::getParam('user.passwordResetTokenExpire', 3600);

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * Generates new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes email verification token
     */
    public function removeEmailVerificationToken(): void
    {
        $this->verification_token = null;
    }

    /**
     * Generates new access token for email verification
     * @throws Exception
     */
    public function generateAccessToken(): void
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Finds user by email
     * @param null|string $email
     * @return User|null
     */
    public static function findByEmail(?string $email): ?User
    {
        return !empty($email)
            ? static::find()
                ->active()
                ->byEmail($email)
                ->one()
            : null;
    }

    /**
     * Return user full name
     * @return string
     */
    public function getFullName(): string
    {
        return Yii::t('users', '{NAME} {SURNAME}', [
            'NAME' => $this->name,
            'SURNAME' => $this->surname
        ]);
    }
}
