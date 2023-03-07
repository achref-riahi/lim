<?php

use PHPUnit\Framework\TestCase;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;
use UI\Command\Validator\PurchaseCigarettesCommandValidator;
use UI\Command\Exception\PurchaseCigarettesCommandBadArgumentsException;

class PurchaseCigarettesCommandValidatorTest extends TestCase
{
    private const CORRECT_AMOUNT_VALUE = '1';
    private const CORRECT_PACKS_VALUE = '1.99';
    private $inputMock;
    private $purchaseCigarettesCommandValidator;

    protected function setUp(): void
    {
        /** @var InputInterface&MockObject */
        $this->inputMock = $this->createMock(InputInterface::class);
        $this->purchaseCigarettesCommandValidator = new PurchaseCigarettesCommandValidator();
    }

    public function testCorrectArgumentsAmountWithDot()
    {
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, $this::CORRECT_PACKS_VALUE);
        $return = $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
        $this->assertTrue($return);
    }

    public function testCorrectArgumentsValuesAmountWithCommaAsSeparator()
    {
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, '1,1');
        $return = $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
        $this->assertTrue($return);
    }

    public function testCorrectArgumentsValuesAmountWithDotAsSeparator()
    {
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, '1.1');
        $return = $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
        $this->assertTrue($return);
    }

    public function testCorrectPacksValueWithBadAmountValueHavingMoreThanTwoFraction()
    {
        $this->expectException(PurchaseCigarettesCommandBadArgumentsException::class);
        $this->expectExceptionMessage('Amount value should be a decimal with dot or comma as separator and with with maximum 2 integer in fraction.');
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, '1,111');
        $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
    }

    public function testCorrectPacksValueWithBadAmountValueHavingSeparatorWithoutFraction()
    {
        $this->expectException(PurchaseCigarettesCommandBadArgumentsException::class);
        $this->expectExceptionMessage('Amount value should be a decimal with dot or comma as separator and with with maximum 2 integer in fraction.');
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, '1,');
        $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
    }

    public function testCorrectPacksValueWithBadAmountValueAsString()
    {
        $this->expectException(PurchaseCigarettesCommandBadArgumentsException::class);
        $this->expectExceptionMessage('Amount value should be a decimal with dot or comma as separator and with with maximum 2 integer in fraction.');
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, 'a');
        $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
    }

    public function testCorrectPacksValueWithBadAmountValueAsZero()
    {
        $this->expectException(PurchaseCigarettesCommandBadArgumentsException::class);
        $this->expectExceptionMessage('Amount value should be greater than 0.');
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls($this::CORRECT_AMOUNT_VALUE, '0');
        $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
    }

    public function testCorrectAmountValueWithBadPacksValueAsString()
    {
        $this->expectException(PurchaseCigarettesCommandBadArgumentsException::class);
        $this->expectExceptionMessage('Packs value accept only integer.');
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls('a', $this::CORRECT_PACKS_VALUE);
        $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
    }

    public function testCorrectAmountValueWithBadPacksValueAsZero()
    {
        $this->expectException(PurchaseCigarettesCommandBadArgumentsException::class);
        $this->expectExceptionMessage('Packs value should be greater than 0.');
        $this->inputMock->method('getArgument')
            ->withConsecutive([$this->equalTo('packs')], [$this->equalTo('amount')])
            ->willReturnOnConsecutiveCalls(0, $this::CORRECT_PACKS_VALUE);
        $this->purchaseCigarettesCommandValidator->validate($this->inputMock);
    }
}
