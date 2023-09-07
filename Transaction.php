<?php

class Transaction {
    private $time;
    private $type;
    private $debit;
    private $credit;
    private $balance;
    private $description;

    public function __construct($type, $debit, $credit, $balance, $description) {
        $this->time = date('Y-m-d H:i:s');
        $this->type = $type;
        $this->debit = $debit;
        $this->credit = $credit;
        $this->balance = $balance;
        $this->description = $description;
    }

    // Getters for transaction properties
}
