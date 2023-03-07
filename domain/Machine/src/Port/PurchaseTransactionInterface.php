<?php

namespace Domain\Machine\Port;

/**
 * Interface PurchaseTransactionInterface
 * @package Domain\Machine
 */
interface PurchaseTransactionInterface
{
    /**
     * @return integer
     */
    public function getItemQuantity() : int;

    /**
     * @return float
     */
    public function getPaidAmount() : float;
}
