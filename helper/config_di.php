<?php

use UI\Command\PurchaseCigarettesCommand;
use Domain\Machine\Action\CigaretteMachine;
use UI\Command\Validator\PurchaseCigarettesCommandValidator;
use Domain\Machine\Validator\CigaretteMachinePurchaseValidator;

return [
    CigaretteMachinePurchaseValidator::class => DI\create(),
    CigaretteMachine::class => DI\create()->constructor(DI\get(CigaretteMachinePurchaseValidator::class)),
    PurchaseCigarettesCommandValidator::class => DI\create(),
    PurchaseCigarettesCommand::class =>  DI\create()->constructor('purchase-cigarettes', DI\get(PurchaseCigarettesCommandValidator::class), DI\get(CigaretteMachine::class))
];
