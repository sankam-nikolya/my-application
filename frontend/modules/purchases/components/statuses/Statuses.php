<?php

namespace purchases\components\statuses;

/**
 * Statuses class
 */
class Statuses
{
    /** @var string[] */
    public const STATUSES_LIST = [
        DraftStatus::class,
        ActiveStatus::class,
    ];

    /**
     * Return default status
     * @return BaseStatus
     */
    public static function getDefaultStatus(): BaseStatus
    {
        return new DraftStatus();
    }

    /**
     *
     * @param int $status
     * @param int $oldStatus
     * @return bool
     */
    public static function isValid(int $status, int $oldStatus): bool
    {
        return self::getStatusByValue($status)->isValid($oldStatus);
    }

    /**
     * Return status list
     * @return array
     */
    public static function getStatuses(): array
    {
        $result = [];

        foreach (self::STATUSES_LIST as $status) {
            /** @var BaseStatus $status */
            $result[$status::VALUE] = new $status();
        }

        return $result;
    }

    /**
     * Get status by value
     * @param int $value
     * @return BaseStatus
     */
    public static function getStatusByValue(int $value): BaseStatus
    {
        return self::getStatuses()[$value] ?? self::getDefaultStatus();
    }

    /**
     * Return status list
     * @return array
     */
    public static function getStatusesList(): array
    {
        $result = [];

        foreach (self::STATUSES_LIST as $status) {
            /** @var BaseStatus $status */
            $result[$status::VALUE] = $status::getStateName();
        }

        return $result;
    }

    /**
     * Return status name
     * @param int|null $value
     * @return string|null
     */
    public static function getStatusName(?int $value): ?string
    {
        return self::getStatusesList()[$value] ?? null;
    }
}
