<?php

require_once __DIR__.'/BillProvider.php';

Class CashRegister {
    private array $bills;

    public function __construct(BillProvider $provider) {
        $this->bills = $provider->getBills();
    }

    public function getBillAmounts(int $amount): ?array {

        if ($amount < 0) {
            return null;
        }

        if ($amount === 0) {
            return [];
        }

        if ($amount > 1000) {
            return $this->calculateBillsForBigAmounts($amount);
        }

        return $this->CalculateBillsToReturn($amount);
    }

    private function calculateBillsToReturn(int $amount): ?array {
        $minBillsRequired = array_fill(0, $amount + 1, PHP_INT_MAX);
        $minBillsRequired[0] = 0;
        $lastBillUsed = array_fill(0, $amount + 1, -1);

        for ($currentAmount = 1; $currentAmount <= $amount; $currentAmount++) {

            // On teste chaque billet disponible
            foreach ($this->bills as $bill) {

                $previousAmount = $currentAmount - $bill;
                $billFitsInAmount = $bill <= $currentAmount;
                $previousAmountIsSolvable = $previousAmount >= 0
                    && $minBillsRequired[$previousAmount] !== PHP_INT_MAX;

                if ($billFitsInAmount && $previousAmountIsSolvable) {

                    $newBillCount = $minBillsRequired[$previousAmount] + 1;
                    $isBetterSolution = $newBillCount < $minBillsRequired[$currentAmount];

                    if ($isBetterSolution) {
                        $minBillsRequired[$currentAmount] = $newBillCount;
                        $lastBillUsed[$currentAmount] = $bill;
                    }
                }
            }
        }

        // Si aucune solution trouvée
        $isImpossible = $minBillsRequired[$amount] === PHP_INT_MAX;
        if ($isImpossible) {
            return null;
        }

        return $this->reconstructBillsFromPath($lastBillUsed, $amount);
    }

    private function reconstructBillsFromPath(array $lastBillUsed, int $amount): array
    {
        $result = [];
        $remainingAmount = $amount;

        while ($remainingAmount > 0) {
            $bill = $lastBillUsed[$remainingAmount];
            $result[$bill] = ($result[$bill] ?? 0) + 1;
            $remainingAmount -= $bill;
        }
        // on trie par ordre croissant
        krsort($result);
        return $result;
    }

    private function calculateBillsForBigAmounts(int $amount): ?array {
        $biggestBill = $this->bills[0];

        $countBills = $this->countBills($amount, $biggestBill);
        $remainingAmount = $amount - $biggestBill * $countBills;

        $billsToReturnOnRemaining = $this->calculateBillsToReturn($remainingAmount);

        if ($billsToReturnOnRemaining === null && $countBills > 0) {
            $countBills--;
            $remainingAmount += $biggestBill;
            $billsToReturnOnRemaining = $this->calculateBillsToReturn($remainingAmount);
        }

        if ($billsToReturnOnRemaining === null) {
            return null;
        }

        $result = $countBills > 0 ? [$biggestBill => $countBills] : [];

        foreach ($billsToReturnOnRemaining as $bill => $qty) {
            $result[$bill] = ($result[$bill] ?? 0) + $qty;
        }

        krsort($result);
        return $result;
    }

    private function countBills(int $amount, int $bill): int {
        return (int) floor($amount / $bill);
    }
}
