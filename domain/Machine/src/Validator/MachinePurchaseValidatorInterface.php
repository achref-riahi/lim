<?php

namespace Domain\Machine\Validator;

use Domain\Machine\Entity\PurchasedItemInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;
use Domain\Machine\Exception\MachineActionBadArgumentsException;

/**
 * Interface MachinePurchaseValidatorInterface
 * @package Domain\Machine
 */
interface MachinePurchaseValidatorInterface
{
    /**
     * Validate machine purchase item action.
     *
     * @param PurchaseTransactionInterface $transaction
     * @param PurchasedItemInterface $purchasedItem
     *
     * @throws MachineActionBadArgumentsException
     *
     * @return boolean
     */
    public function validate(PurchaseTransactionInterface $transaction, PurchasedItemInterface $purchasedItem): bool;
}
