<?php

namespace Domain\Machine\Validator;

use Symfony\Component\Validator\Validation;
use Domain\Machine\Entity\PurchasedItemInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Domain\Machine\Exception\MachineActionBadArgumentsException;

/**
 * Interface CigaretteMachinePurchaseValidator
 * @package Domain\Machine
 */
class CigaretteMachinePurchaseValidator implements MachinePurchaseValidatorInterface
{
    /** @inheritDoc */
    public function validate(PurchaseTransactionInterface $transaction, PurchasedItemInterface $purchasedItem): bool
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate(
            [
                'item_quantity' => $transaction->getItemQuantity(),
                'paid_amount' => $transaction->getPaidAmount(),
            ],
            $this->constraints($purchasedItem)
        );

        if ($violations->count() > 0) {
            throw new MachineActionBadArgumentsException($violations);
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param PurchasedItemInterface $purchasedItem
     *
     * @return Symfony\Component\Validator\Constraints\Collection
     */
    private function constraints(PurchasedItemInterface $purchasedItem): Assert\Collection
    {
        return new Assert\Collection([
            'item_quantity' => [
                new Assert\Type([
                    'type' => 'int',
                    'message' => 'Item quantity value should be an integer.'
                ]),
                new Assert\GreaterThan([
                    'value' => 0,
                    'message' => 'Item quantity value should be greater than 0.'
                ]),
            ],
            'paid_amount' => [
                new Assert\Type([
                    'type' => 'float',
                    'message' => 'Paid amount value should be a float.'
                ]),
                new Assert\GreaterThan([
                    'value' => 0,
                    'message' => 'Paid amount value should be greater than 0.'
                ]),
                new Assert\GreaterThanOrEqual([
                    'value' => $purchasedItem->getTotalAmount(),
                    'message' => 'Paid amount value should be greater than total amount.'
                ]),
            ]
        ]);
    }
}
