<?php

namespace purchases\components\statuses;

use Yii;

class ActiveStatus extends BaseStatus
{
    public const VALUE = 1;

    /**
     * @inheritDoc
     */
    public function isValid($oldState): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getStateName(): string
    {
        return Yii::t('purchases', 'Active');
    }
}
