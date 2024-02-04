<?php

namespace users\models\queries;

use common\components\db\ActiveQuery;
use users\models\User;

/**
 * This is the ActiveQuery class for [[\users\User]].
 *
 * @see \users\models\User
 */
class UserQuery extends ActiveQuery
{
    /**
     * Return active users
     * @return UserQuery
     */
    public function active(): UserQuery
    {
        return $this->andWhere([
            'status' => User::STATUS_ACTIVE
        ]);
    }

    /**
     * Return inactive users
     * @return UserQuery
     */
    public function inActive(): UserQuery
    {
        return $this->andWhere([
            'status' => User::STATUS_INACTIVE
        ]);
    }

    /**
     * Return inactive users
     * @return UserQuery
     */
    public function deleted(): UserQuery
    {
        return $this->andWhere([
            'status' => User::STATUS_DELETED
        ]);
    }

    /**
     * Find by `id`
     * @param integer $id
     * @return UserQuery
     */
    public function byId(int $id): UserQuery
    {
        return $this->andWhere([
            'id' => $id
        ]);
    }

    /**
     * Find by `email`
     * @param string $email
     * @return UserQuery
     */
    public function byEmail(string $email): UserQuery
    {
        return $this->andWhere([
            'email' => $email
        ]);
    }

    /**
     * Find by `password_reset_token`
     * @param string $token
     * @return UserQuery
     */
    public function byPasswordResetToken(string $token): UserQuery
    {
        return $this->andWhere([
            'password_reset_token' => $token
        ]);
    }

    /**
     * Find by `access_token`
     * @param string $token
     * @return UserQuery
     */
    public function byAccessToken(string $token): UserQuery
    {
        return $this->andWhere([
            'access_token' => $token
        ]);
    }

    /**
     * Find by `verification_token`
     * @param string $token
     * @return UserQuery
     */
    public function byVerificationToken(string $token): UserQuery
    {
        return $this->andWhere([
            'verification_token' => $token
        ]);
    }
}
