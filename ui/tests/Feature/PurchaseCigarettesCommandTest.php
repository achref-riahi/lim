<?php

namespace UI\Tests\Feature;

use ReflectionObject;

use PHPUnit\Framework\TestCase;

use function Helper\getContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class PurchaseCigarettesCommandTest extends TestCase
{
    private const SUCCESS = 0;
    private const FAILURE = 1;

    private const CHANGE_MESSAGES = ['Your change is:', 'Coins', 'Count'];

    protected Command $application;

    protected function setUp(): void
    {
        $this->application = getContainer()->get(
            \UI\Command\PurchaseCigarettesCommand::class
        );
    }

    private function getCommandTester()
    {
        return new CommandTester($this->application);
    }

    private function getMachine()
    {
        $applicationReflection = new ReflectionObject($this->application);
        $machineProperty = $applicationReflection->getProperty('machine');
        $machineProperty->setAccessible(true);
        return $machineProperty->getValue($this->application);
    }

    private function getAmount(string $packs, float $payload = 0)
    {
        $value = ((int) $packs * $this->getMachine()::ITEM_PRICE) + $payload;
        return number_format($value, 2, ',', '');
    }

    public function testSuccessOnEqualPaidAndTotalAmountsMultiplePacksMessage()
    {
        $commandTester = $this->getCommandTester();
        $packs = '2';
        $amount = $this->getAmount($packs);
        $commandTester->execute([
            'packs' => $packs,
            'amount' => $amount
        ]);
        $this->assertEquals($this::SUCCESS, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            "You bought $packs packs of cigarettes for -".$this->getAmount($packs)."€, each for -".$this->getAmount(1).'€.',
            $output
        );
        foreach ($this::CHANGE_MESSAGES as $message){
            $this->assertStringNotContainsString($message, $output);
        }
    }

    public function testSuccessOnEqualPaidAndTotalAmountsOnePackMessage()
    {
        $commandTester = $this->getCommandTester();
        $packs = '1';
        $amount = $this->getAmount($packs);
        $commandTester->execute([
            'packs' => $packs,
            'amount' => $amount
        ]);
        $this->assertEquals($this::SUCCESS, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            "You bought $packs pack of cigarettes for -".$this->getAmount($packs)."€, the one pack price is for -".$this->getAmount(1).'€.',
            $output
        );
        foreach ($this::CHANGE_MESSAGES as $message){
            $this->assertStringNotContainsString($message, $output);
        }
    }

    public function testSuccessOnPaidAmountGreaterThanTotalAmount()
    {
        $commandTester = $this->getCommandTester();
        $packs = '2';
        $amount = $this->getAmount($packs, 1);
        $commandTester->execute([
            'packs' => $packs,
            'amount' => $amount
        ]);
        $this->assertEquals($this::SUCCESS, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            "You bought $packs packs of cigarettes for -".$this->getAmount($packs)."€, each for -".$this->getAmount(1).'€.',
            $output
        );
        foreach ($this::CHANGE_MESSAGES as $message){
            $this->assertStringContainsString($message, $output);
        }
    }

    public function testFailOnLessPaidAmountThanTotalAmount()
    {
        $commandTester = $this->getCommandTester();
        $packs = '2';
        $amount = $this->getAmount($packs, -1);
        $commandTester->execute([
            'packs' => $packs,
            'amount' => $amount
        ]);
        $this->assertEquals($this::FAILURE, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(
            "You bought $packs packs of cigarettes for -".$this->getAmount($packs)."€, each for -".$this->getAmount(1).'€.',
            $output
        );
        $this->assertStringContainsString(
            'Paid amount value should be greater than total amount.',
            $output
        );
    }
}
