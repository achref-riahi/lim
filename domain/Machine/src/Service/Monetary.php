<?php

namespace Domain\Machine\Service;

/**
 * Class Monetary
 * @package Domain\Machine
 */
class Monetary
{
    public const COINS = [2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01];

    /**
     * Denomination of an amount.
     *
     * @param float $amount
     * @return array
     */
    public static function denomination(float $amount): array
    {
        $amount *= 100;
        foreach (self::COINS as $coin) {
            if ($amount <= 0) {
                break;
            }
            $adaptedCoinValue = $coin * 100;
            $count =  (int)floor($amount / $adaptedCoinValue);
            if ($count == 0) {
                continue;
            }
            $amount = $amount - ($count * $adaptedCoinValue);
            $result[] = [
                'coin' => $coin,
                'count' => $count
            ];
        }
        return $result ?? [];
    }
}
