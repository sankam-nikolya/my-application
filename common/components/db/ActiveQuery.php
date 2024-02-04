<?php

namespace common\components\db;

use yii\db\ActiveQuery as BaseActiveQuery;

/**
 * Class ActiveQuery
 * @package common\components\db
 */
class ActiveQuery extends BaseActiveQuery
{
    /**
     * {@inheritdoc}
     */
    public function one($db = null)
    {
        $this->limit(1);

        return parent::one($db);
    }
}