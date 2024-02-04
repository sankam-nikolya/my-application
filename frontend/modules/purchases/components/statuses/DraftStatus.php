<?php

namespace purchases\components\statuses;

use Yii;

class DraftStatus extends BaseStatus
{
    public const VALUE = 0;

    /**
     * @inheritDoc
     */
    public function isValid(?int $oldState): bool
    {
        return $oldState !== ActiveStatus::VALUE;
    }

    /**
     * @inheritDoc
     */
    public static function getStateName(): string
    {
        return Yii::t('purchases', 'Draft');
    }
}
