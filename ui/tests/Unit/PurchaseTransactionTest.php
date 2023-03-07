<?php

namespace UI\Tests\Unit;

use PHPUnit\Framework\TestCase;

use UI\Command\Adapter\PurchaseTransaction;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;

class PurchaseTransactionTest extends TestCase
{
    protected $inputMock;

    protected function setUp(): void
    {
        /** @var InputInterface&MockObject */
        $this->inputMock = $this->createMock(InputInterface::class);
    }

    public function testGetItemQuantityWithValidIntegerArgument()
    {
        $this->inputMock->method('getArgument')
            ->with($this->equalTo('packs'))
            ->willReturn('1');
        $this->assertEquals(1, (new PurchaseTransaction($this->inputMock))->getItemQuantity());
    }

    public function testGetItemQuantityWithBadArguments()
    {
        foreach (['t', 'test', true, false, '1.', '-1', '1.1'] as $badArgument) {
            $this->inputMock->method('getArgument')
                ->with($this->equalTo('packs'))
                ->willReturn($badArgument);
            $this->assertIsInt((new PurchaseTransaction($this->inputMock))->getItemQuantity());
        }
    }

    public function testGetPaidAmountWithValidFloatWithDotArgument()
    {
        $this->inputMock->method('getArgument')
            ->with($this->equalTo('amount'))
            ->willReturn('1.1');
        $this->assertEquals(1.1, (new PurchaseTransaction($this->inputMock))->getPaidAmount());
    }

    public function testGetPaidAmountWithValidFloatWithCommaArgument()
    {
        $this->inputMock->method('getArgument')
            ->with($this->equalTo('amount'))
            ->willReturn('1,1');
        $this->assertEquals(1.1, (new PurchaseTransaction($this->inputMock))->getPaidAmount());
    }

    public function testGetPaidAmountWithBadArgument()
    {
        foreach (['t', 'test', true, false, '1.'] as $badArgument) {
            $this->inputMock->method('getArgument')
                ->with($this->equalTo('amount'))
                ->willReturn($badArgument);
            $this->assertIsFloat((new PurchaseTransaction($this->inputMock))->getPaidAmount());
        }
    }
}
