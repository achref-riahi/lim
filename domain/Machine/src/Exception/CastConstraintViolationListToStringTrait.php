<?php

namespace Domain\Machine\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Trait CastConstraintViolationListToStringTrait
 * @package Domain\Machine
 */
trait CastConstraintViolationListToStringTrait
{
    private function castConstraintViolationListToString(ConstraintViolationListInterface $violations): string
    {
        return implode("\n", array_map(fn ($violation): string => $violation->getMessage(), [...$violations]));
    }
}