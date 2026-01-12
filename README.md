# Cashier 💰

Cette application permet d'encoder un montant et elle affichera le nombre de billet à rendre pour celui-ci.

## Installation
```
git clone https://github.com/Jokzin/cashier.git
cd cashier
composer install
```

## Test l'application dans le navigateur
```
php -S localhost:8080 -t public
http://localhost:8080/?amount=23
```

## Lancer les tests
```
./vendor/bin/phpunit
```

## Structure
```
cashier/
├── data/
│   └── bills.json  
├── public/
│   └── index.php          
├── src/
│   ├── CashRegister.php   
│   ├── BillProvider.php   
│   ├── DataProvider.php
├── tests/
│   └── CashRegisterTest.php
├── composer.json
├── phpunit.xml
└── README.md
```
