<?php

namespace UI\Command\Validator;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use UI\Command\Exception\PurchaseCigarettesCommandBadArgumentsException;

/**
 * Class PurchaseCigarettesCommandValidator
 * @package UI\Command
 */
class PurchaseCigarettesCommandValidator implements CommandInputsValidatorInterface
{
    /** @inheritDoc */
    public function validate(InputInterface $input): bool
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate(
            [
                'packs' => $input->getArgument('packs'),
                'amount' => $input->getArgument('amount'),
            ],
            $this->constraints()
        );
        if ($violations->count() > 0) {
            throw new PurchaseCigarettesCommandBadArgumentsException($violations);
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Symfony\Component\Validator\Constraints\Collection
     */
    private function constraints(): Collection
    {
        return new Assert\Collection([
            'packs' => [
                new Assert\GreaterThan([
                    'value' => 0,
                    'message' => 'Packs value should be greater than 0.'
                ]),
                new Assert\Regex([
                    'pattern' => '/^[0-9]+$/',
                    'message' => 'Packs value accept only integer.'
                ])
            ],
            'amount' => [
                new Assert\GreaterThan([
                    'value' => 0,
                    'message' => 'Amount value should be greater than 0.'
                ]),
                new Assert\Regex([
                    'pattern' => '/^[0-9]*(?:[\.\,][0-9]{1,2})?$/',
                    'message' => 'Amount value should be a decimal with dot or comma as separator and with with maximum 2 integer in fraction.'
                ])
            ]
        ]);
    }
}
