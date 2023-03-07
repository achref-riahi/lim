<?php

namespace Domain\Machine\Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use Domain\Machine\Service\Monetary;
use Domain\Machine\Action\CigaretteMachine;
use Domain\Machine\Action\MachineInterface;
use Domain\Machine\Entity\PurchasedCigarette;
use Domain\Machine\Port\PurchaseTransactionInterface;

class PurchasedCigaretteTest extends TestCase
{
    protected $transactionMock;
    protected $machineMock;
    protected $purchasedCigarette;
    protected function setUp(): void
    {
        /** @var PurchaseTransactionInterface&MockObject */
        $this->transactionMock = $this->createMock(PurchaseTransactionInterface::class);
        /** @var MachineInterface&MockObject */
        $this->machineMock = $this->createMock(CigaretteMachine::class);
        $this->purchasedCigarette = new PurchasedCigarette($this->transactionMock, $this->machineMock);
    }

    public function testGetItemQuantity()
    {
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $return = $this->purchasedCigarette->getItemQuantity();
        $this->assertIsInt($return);
        $this->assertEquals(1, $return);
    }

    public function testGetTotalAmount()
    {
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $return = $this->purchasedCigarette->getTotalAmount();
        $this->assertIsFloat($return);
        $this->assertEquals($this->machineMock::ITEM_PRICE, $return);
    }

    public function testGetChangeHandleBadNegativeDifference()
    {
        $this->transactionMock->method('getPaidAmount')
            ->willReturn($this->machineMock::ITEM_PRICE - 1);
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $return = $this->purchasedCigarette->getChange();
        $this->assertIsArray($return);
        $this->assertEmpty($return);
    }

    public function testGetChangeHandleDifference()
    {
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $this->transactionMock->method('getPaidAmount')
        ->willReturnOnConsecutiveCalls(
            ...array_map(fn ($coin) => $this->machineMock::ITEM_PRICE + $coin, Monetary::COINS)
        );
        foreach (Monetary::COINS as $coin) {
            $return = $this->purchasedCigarette->getChange();
            $this->assertIsArray($return);
            $this->assertContains(['coin' => $coin, 'count' => 1], $return);
        }
    }

    public function testGetChangeThatSumAndItemPriceEqualToPaidAmount()
    {
        $this->transactionMock->method('getItemQuantity')
            ->willReturn(1);
        $this->transactionMock->method('getPaidAmount')
            ->willReturn($this->machineMock::ITEM_PRICE + 99.99);
        $return = $this->purchasedCigarette->getChange();
        $totalChange = array_map(function($item) {
            return $item['coin'] * $item['count'];
        }, $return);
        $this->assertEquals(array_sum($totalChange), 99.99);
    }
}
