<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
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
    #[DataProvider('amountsThatReturnNull')]
    public function it_returns_null_for_unsplittable_amounts(int $amount): void
    {
        $result = $this->cashRegister->getBillAmounts($amount);

        $this->assertNull($result);
    }

    #[Test]
    #[DataProvider('amountsThatCanBeDecomposed')]
    public function it_decomposes_valid_amounts(int $amount, array $expectedBills): void
    {
        $result = $this->cashRegister->getBillAmounts($amount);

        $this->assertSame($expectedBills, $result);
    }

    public static function amountsThatReturnNull(): array
    {
        return [
            'amount 1' => [1],
        ];
    }

    public static function amountsThatCanBeDecomposed(): array
    {
        $maxAmount = 9007199254740991;
        $maxAmountTens = intdiv($maxAmount, 10) - 1;

        return [
            'amount 6' => [6, [2 => 3]],
            'amount 10' => [10, [10 => 1]],
            'amount 11' => [11, [5 => 1, 2 => 3]],
            'amount 21' => [21, [10 => 1, 5 => 1, 2 => 3]],
            'amount 23' => [23, [10 => 1, 5 => 1, 2 => 4]],
            'amount 31' => [31, [10 => 2, 5 => 1, 2 => 3]],
            'amount max int' => [$maxAmount, [10 => $maxAmountTens, 5 => 1, 2 => 3]],
        ];
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
