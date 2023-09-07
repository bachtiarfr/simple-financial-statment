<?php

class User {
    private $username;
    private $password;
    private $balance;
    private $transactions;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        $this->balance = 0;
        $this->transactions = [];
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setBalance($balance) {
        $this->balance = $balance;
    }

    public function getTransactions() {
        return $this->transactions;
    }

    public function addTransaction($transaction) {
        $this->transactions[] = $transaction;
    }
}
