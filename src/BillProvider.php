<?php

interface BillProvider {
    /*
     * @return int[] Liste de billets disponibles
     */
    public function getBills(): array;
}
