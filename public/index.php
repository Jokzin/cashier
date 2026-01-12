<?php

require_once __DIR__.'/../src/DataProvider.php';
require_once __DIR__.'/../src/CashRegister.php';

$amount = (int) ($_GET['amount'] ?? 0);

$data = new DataProvider(__DIR__.'/../data/bills.json');
$register = new CashRegister($data);

$result = $register->getBillAmounts($amount);
header('Content-Type: application/json');

echo json_encode([
    'Montant' => $amount,
    'Billets' => $result
]);
