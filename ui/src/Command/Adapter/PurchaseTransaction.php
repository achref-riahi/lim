<?php

namespace UI\Command\Adapter;

use Symfony\Component\Console\Input\InputInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;

/**
 * Class PurchaseTransaction
 * @package UI\Command
 */
class PurchaseTransaction implements PurchaseTransactionInterface
{
    private InputInterface $input;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /** @inheritDoc */
    public function getItemQuantity(): int
    {
        return (int) $this->input->getArgument('packs');
    }

    /** @inheritDoc */
    public function getPaidAmount(): float
    {
        return (float) \str_replace(',', '.', $this->input->getArgument('amount') ?? '0.0');
    }
}
