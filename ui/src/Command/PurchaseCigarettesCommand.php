<?php

namespace UI\Command;

use Domain\Machine\Action\CigaretteMachine;
use Domain\Machine\Action\MachineInterface;
use Symfony\Component\Console\Helper\Table;
use UI\Command\Adapter\PurchaseTransaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UI\Command\Validator\CommandInputsValidatorInterface;
use UI\Command\Validator\PurchaseCigarettesCommandValidator;

/**
 * Class PurchaseCigarettesCommand
 * @package UI\Command
 */
class PurchaseCigarettesCommand extends Command
{
    /** @var CigaretteMachine */
    private MachineInterface $machine;

    /** @var PurchaseCigarettesCommandValidator */
    private CommandInputsValidatorInterface $validator;

    /**
     * PurchaseCigarettesCommand constructor.
     *
     * @param string $name
     * @param CommandInputsValidatorInterface $validator
     * @param MachineInterface $machine
     */
    public function __construct(string $name, CommandInputsValidatorInterface $validator, MachineInterface $machine)
    {
        $this->machine = $machine;
        $this->validator = $validator;
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('packs', InputArgument::REQUIRED, "How many packs do you want to buy?");
        $this->addArgument('amount', InputArgument::REQUIRED, "The amount in euro.");
    }

    /**
     * Add '-' as prefix, '€' as suffix, and set ',' as decimal separator.
     *
     * @param float $value
     * @return string
     */
    private function formatMoneyString(float $value): string
    {
        return '-'.number_format($value, 2, ',', '').'€';
    }

    /**
     * Get input info message string: user inputs, machine item cost.
     *
     * @param InputInterface $input
     *
     * @return string
     */
    private function getTransactionInfoMessage(InputInterface $input): string
    {
        $packs = (int) $input->getArgument('packs');
        $total = $this->formatMoneyString((float) ($packs * $this->machine::ITEM_PRICE));
        $unitPrice = $this->formatMoneyString($this->machine::ITEM_PRICE);
        $packWording = 'pack'.($packs > 1 ? 's' : '');
        $unitPricePrefixWording = $packs > 1 ? 'each for' : 'the one pack price is for';
        return  "You bought <info>$packs</info> $packWording of cigarettes ".
                "for <info>$total</info>,".
                " $unitPricePrefixWording <info>$unitPrice</info>.";
    }

    /**
     * Validate inputs.
     *
     * @param InputInterface $input
     *
     * @return boolean
     */
    private function validateInputs(InputInterface $input): bool
    {
        return $this->validator->validate($input);
    }

    /**
     * @param InputInterface   $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        try {
            $this->validateInputs($input);
            $output->writeln($this->getTransactionInfoMessage($input));
            $transaction = new PurchaseTransaction($input);
            $purchased = $this->machine->execute($transaction);
            $change = $purchased->getChange();
            if (!empty($change)) {
                $output->writeln('Your change is:');
                $table = new Table($output);
                $table
                    ->setHeaders(array('Coins', 'Count'))
                    ->setRows($purchased->getChange())
                ;
                $table->render();
            }
            return 0;
        } catch(\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return 1;
        }
    }
}
