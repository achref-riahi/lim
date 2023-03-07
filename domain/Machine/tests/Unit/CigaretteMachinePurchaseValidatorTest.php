<?php

namespace Domain\Machine\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Domain\Machine\Entity\PurchasedItemInterface;
use Domain\Machine\Port\PurchaseTransactionInterface;
use Domain\Machine\Validator\CigaretteMachinePurchaseValidator;
use Domain\Machine\Exception\MachineActionBadArgumentsException;

class CigaretteMachinePurchaseValidatorTest extends TestCase
{
    private $transactionMock;
    private $purchasedItemMock;
    
    protected function setUp() : void
    {
        /** @var PurchaseTransactionInterface&MockObject */
        $this->transactionMock = $this->createMock(PurchaseTransactionInterface::class);
        /** @var PurchasedItemInterface&MockObject */
        $this->purchasedItemMock = $this->createMock(PurchasedItemInterface::class);
        $this->cigaretteMachinePurchaseValidator = new CigaretteMachinePurchaseValidator();
    }

    public function testSuccussOnPaidAmountGreaterThanTotalAmount()
    {
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $this->transactionMock->method('getPaidAmount')
            ->willReturn(10.0);
        $this->purchasedItemMock->method('getTotalAmount')
            ->willReturn(1.0);
        $return = $this->cigaretteMachinePurchaseValidator->validate($this->transactionMock, $this->purchasedItemMock);
        $this->assertTrue($return);
    }

    public function testFailOnPaidAmountLessThanTotalAmount()
    {
        $this->expectException(MachineActionBadArgumentsException::class);
        $this->expectExceptionMessage('Paid amount value should be greater than total amount.');
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $this->transactionMock->method('getPaidAmount')
            ->willReturn(1.0);
        $this->purchasedItemMock->method('getTotalAmount')
            ->willReturn(10.0);
        $return = $this->cigaretteMachinePurchaseValidator->validate($this->transactionMock, $this->purchasedItemMock);
        $this->assertFalse($return);
    }

    public function testFailOnZeroItemQuanity()
    {
        $this->expectException(MachineActionBadArgumentsException::class);
        $this->expectExceptionMessage('Item quantity value should be greater than 0.');
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(0);
        $this->transactionMock->method('getPaidAmount')
            ->willReturn(1.0);
        $this->purchasedItemMock->method('getTotalAmount')
            ->willReturn(1.0);
        $return = $this->cigaretteMachinePurchaseValidator->validate($this->transactionMock, $this->purchasedItemMock);
        $this->assertFalse($return);
    }

    public function testFailOnZeroPaidAmount()
    {
        $this->expectException(MachineActionBadArgumentsException::class);
        $this->expectExceptionMessage('Paid amount value should be greater than 0.');
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $this->transactionMock->method('getPaidAmount')
            ->willReturn(0.0);
        $this->purchasedItemMock->method('getTotalAmount')
            ->willReturn(1.0);
        $return = $this->cigaretteMachinePurchaseValidator->validate($this->transactionMock, $this->purchasedItemMock);
        $this->assertFalse($return);
    }
}