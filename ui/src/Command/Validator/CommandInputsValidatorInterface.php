<?php

namespace UI\Command\Validator;

use Symfony\Component\Console\Input\InputInterface;
use UI\Command\Exception\PurchaseCigarettesCommandBadArgumentsException;

/**
 * Interface CommandInputsValidatorInterface
 * @package UI\Command
 */
interface CommandInputsValidatorInterface
{
   /**
    * Validate purchase cigarettes command inputs.
    *
    * @param InputInterface $input
    * 
    * @throws PurchaseCigarettesCommandBadArgumentsException 
    *
    * @return boolean
    */
    public function validate(InputInterface $input): bool;
}