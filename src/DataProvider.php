<?php

require_once __DIR__.'/BillProvider.php';

class DataProvider implements BillProvider {
    private string $file;

    public function __construct(string $file) {
        $this->file = $file;
    }

    public function getBills(): array {
        $data = json_decode(file_get_contents($this->file), true);
        $bills = $data['bills'] ?? [];
        // on trie par ordre décroissant
        rsort($bills);
        return $bills;
    }
}
