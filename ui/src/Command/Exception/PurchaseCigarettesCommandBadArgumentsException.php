<?php

namespace UI\Command\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class PurchaseCigarettesCommandBadArgumentsException
 * @package UI\Command
 */
class PurchaseCigarettesCommandBadArgumentsException extends \Exception 
{
    use CastConstraintViolationListToStringTrait;
    
    public function __construct(ConstraintViolationListInterface $violations){
        parent::__construct($this->castConstraintViolationListToString($violations));
    }
}