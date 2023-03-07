<?php

namespace Domain\Machine\Action;

use Domain\Machine\Entity\PurchasedCigarette;
use Domain\Machine\Entity\PurchasedItemInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;
use Domain\Machine\Validator\CigaretteMachinePurchaseValidator;
use Domain\Machine\Validator\MachinePurchaseValidatorInterface;

/**
 * Class CigaretteMachine
 * @package Domain\Machine
 */
class CigaretteMachine implements MachineInterface
{
    public const ITEM_PRICE = 4.99;

    /** @var CigaretteMachinePurchaseValidator */
    private MachinePurchaseValidatorInterface $validator;

    public function __construct(MachinePurchaseValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate transaction.
     *
     * @param PurchaseTransactionInterface $transaction
     * @param PurchasedItemInterface $purchasedItem
     *
     * @return boolean
     */
    private function validate(PurchaseTransactionInterface $transaction, PurchasedItemInterface $purchasedItem): bool
    {
        return $this->validator->validate($transaction, $purchasedItem);
    }

    /** @inheritDoc */
    public function execute(PurchaseTransactionInterface $purchaseTransaction): PurchasedItemInterface
    {
        $purchasedCigarette = new PurchasedCigarette($purchaseTransaction, $this);
        $this->validate($purchaseTransaction, $purchasedCigarette);
        return $purchasedCigarette;
    }
}
