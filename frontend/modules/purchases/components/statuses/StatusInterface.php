<?php

namespace purchases\components\statuses;

interface StatusInterface
{
    /**
     * Can state change
     * @param int|null $oldState
     * @return bool
     */
    public function isValid(?int $oldState): bool;

    /**
     * Return state name
     * @return string
     */
    public static function getStateName(): string;
}
