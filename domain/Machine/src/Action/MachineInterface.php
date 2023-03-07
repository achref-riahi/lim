<?php

namespace Domain\Machine\Action;

use Domain\Machine\Entity\PurchasedItemInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;

/**
 * Interface MachineInterface
 * @package Domain\Machine
 */
interface MachineInterface
{
    /**
     * @param PurchaseTransactionInterface $purchaseTransaction
     *
     * @return PurchasedItemInterface
     */
    public function execute(PurchaseTransactionInterface $purchaseTransaction): PurchasedItemInterface;
}
