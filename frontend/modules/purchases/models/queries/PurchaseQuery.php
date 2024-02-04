<?php

namespace purchases\models\queries;

use common\components\db\ActiveQuery;
use purchases\components\statuses\ActiveStatus;
use Yii;

/**
 * This is the ActiveQuery class for [[\purchases\models\Purchase]].
 *
 * @see \purchases\models\Purchase
 */
class PurchaseQuery extends ActiveQuery
{
    /**
     * Return active purchases
     * @return PurchaseQuery
     */
    public function active(): PurchaseQuery
    {
        return $this->andWhere([
            'status' => ActiveStatus::VALUE,
        ]);
    }

    /**
     * Return purchase by id
     * @param int $id
     * @return PurchaseQuery
     */
    public function byId(int $id): PurchaseQuery
    {
        return $this->andWhere([
            'id' => $id
        ]);
    }

    /**
     * Return purchase for user by it's id
     * @param int|null $userId
     * @return PurchaseQuery
     */
    public function byUser(?int $userId): PurchaseQuery
    {
        return $this->andWhere([
            'created_by' => $userId
        ]);
    }

    /**
     * Return purchase for current user
     * @return PurchaseQuery
     */
    public function forCurrentUser(): PurchaseQuery
    {
        return $this->byUser(Yii::$app->user->id);
    }
}
