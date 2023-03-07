<?php

namespace Domain\Machine\Entity;

/**
 * Interface PurchasedItemInterface
 * @package Domain\Machine
 */
interface PurchasedItemInterface
{
    /**
     * @return integer
     */
    public function getItemQuantity(): int;

    /**
     * @return float
     */
    public function getTotalAmount(): float;

    /**
     * Returns the change in this format:
     *
     * Coin Count
     * 0.01 0
     * 0.02 0
     * .... .....
     *
     * @return array
     */
    public function getChange();
}
