<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__.'/../src/CashRegister.php';
require_once __DIR__.'/../src/BillProvider.php';

class CashRegisterTest extends TestCase
{
    private CashRegister $cashRegister;

    protected function setUp(): void
    {
        $provider = $this->createMock(BillProvider::class);
        $provider->method('getBills')->willReturn([10, 5, 2]);
        $this->cashRegister = new CashRegister($provider);
    }

    #[Test]
    public function amount_1(): void
    {
        $result = $this->cashRegister->getBillAmounts(1);

        $this->assertNull($result);
    }

    #[Test]
    public function amount_6(): void
    {
        $result = $this->cashRegister->getBillAmounts(6);

        $this->assertSame([2 => 3], $result);
    }

    #[Test]
    public function amount_10(): void
    {
        $result = $this->cashRegister->getBillAmounts(10);

        $this->assertNotNull($result);
        $this->assertSame(10, $this->calculateSum($result));
    }

    #[Test]
    public function amount_11(): void
    {
        $result = $this->cashRegister->getBillAmounts(11);

        $this->assertNotNull($result);
        $this->assertSame(11, $this->calculateSum($result));
    }

    #[Test]
    public function amount_21(): void
    {
        $result = $this->cashRegister->getBillAmounts(21);

        $this->assertNotNull($result);
        $this->assertSame(21, $this->calculateSum($result));
    }

    #[Test]
    public function amount_23(): void
    {
        $result = $this->cashRegister->getBillAmounts(23);

        $this->assertNotNull($result);
        $this->assertSame(23, $this->calculateSum($result));
    }

    #[Test]
    public function amount_31(): void
    {
        $result = $this->cashRegister->getBillAmounts(31);

        $this->assertNotNull($result);
        $this->assertSame(31, $this->calculateSum($result));
    }

    #[Test]
    public function amount_9007199254740991(): void
    {
        $result = $this->cashRegister->getBillAmounts(9007199254740991);

        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertSame(9007199254740991, $this->calculateSum($result));
    }



    private function calculateSum(array $bills): int
    {
        $sum = 0;
        foreach ($bills as $bill => $count) {
            $sum += $bill * $count;
        }
        return $sum;
    }
}
