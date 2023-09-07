<?php

require_once 'classes/User.php';

class FinancialStatement
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function deposit($amount)
    {
        return $this->user->deposit($amount);
    }

    public function withdraw($amount)
    {
        return $this->user->withdraw($amount);
    }

    public function recordTransaction($type, $amount, $description = '')
    {
        $this->user->addTransaction($type, $amount, $description);
    }

    public function getTransactionHistory()
    {
        return $this->user->getTransactions();
    }

    public function getBalance()
    {
        return $this->user->getBalance();
    }
}

?>
