<?php

namespace purchases\models\queries;

use common\components\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\purchases\models\PurchaseNomenclature]].
 *
 * @see \purchases\models\PurchaseNomenclature
 */
class PurchaseNomenclatureQuery extends ActiveQuery
{
    /**
     * Return purchase nomenclatures by purchase id
     * @param int $id
     * @return PurchaseNomenclatureQuery
     */
    public function byPurchase(int $id): PurchaseNomenclatureQuery
    {
        return $this->andWhere([
            'purchase_id' => $id
        ]);
    }

    /**
     * Return purchase nomenclatures by ids
     * @param array $ids
     * @return PurchaseNomenclatureQuery
     */
    public function byIds(array $ids): PurchaseNomenclatureQuery
    {
        return $this->andWhere([
            'id' => $ids
        ]);
    }
}
