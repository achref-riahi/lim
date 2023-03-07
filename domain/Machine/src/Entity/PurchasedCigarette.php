<?php

namespace Domain\Machine\Entity;

use Domain\Machine\Service\Monetary;
use Domain\Machine\Action\CigaretteMachine;
use Domain\Machine\Action\MachineInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;

/**
 * Class PurchasedCigarette
 * @package Domain\Machine
 */
class PurchasedCigarette implements PurchasedItemInterface
{
    /** @var PurchaseTransactionInterface */
    private PurchaseTransactionInterface $transaction;

    /** @var CigaretteMachine */
    private MachineInterface $machine;

    /**
     * PurchasedCigarette constructor.
     *
     * @param PurchaseTransactionInterface $transaction
     * @param MachineInterface $machine
     */
    public function __construct(PurchaseTransactionInterface $transaction, MachineInterface $machine)
    {
        $this->transaction = $transaction;
        $this->machine = $machine;
    }

    /** @inheritDoc */
    public function getItemQuantity(): int
    {
        return $this->transaction->getItemQuantity();
    }

    /** @inheritDoc */
    public function getTotalAmount(): float
    {
        return $this->getItemQuantity() * $this->machine::ITEM_PRICE;
    }

    /** @inheritDoc */
    public function getChange(): array
    {
        return Monetary::denomination($this->getDifference());
    }

    /**
     * Get difference between paid mount and total amount.
     *
     * @return float
     */
    private function getDifference(): float
    {
        return round($this->transaction->getPaidAmount() - $this->getTotalAmount(), 2);
    }
}
