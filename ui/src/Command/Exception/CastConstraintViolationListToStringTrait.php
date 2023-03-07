<?php

namespace UI\Command\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Trait CastConstraintViolationListToStringTrait
 * @package UI\Command
 */
trait CastConstraintViolationListToStringTrait
{
    private function castConstraintViolationListToString(ConstraintViolationListInterface $violations): string
    {
        return implode("\n", array_map(fn ($violation): string => $violation->getMessage(), [...$violations]));
    }
}