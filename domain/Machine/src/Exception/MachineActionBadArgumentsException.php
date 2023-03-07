<?php

namespace Domain\Machine\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class MachineActionBadArgumentsException
 * @package Domain\Machine
 */
class MachineActionBadArgumentsException extends \Exception
{
    use CastConstraintViolationListToStringTrait;
    public function __construct(ConstraintViolationListInterface $violations){
        parent::__construct($this->castConstraintViolationListToString($violations));
    }
}
