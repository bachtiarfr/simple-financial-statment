<?php

class User
{
    private $userDataDirectory = 'internal/user_data/';
    private $username;
    private $balance;
    private $transactions = [];

    public function __construct($username)
    {
        $this->username = $username;
        $this->loadUserData();
    }

    public function deposit($amount)
    {
        $_SESSION['balance'] += $amount;
        $this->balance = $_SESSION['balance'];
        $this->addTransaction('Deposit', $amount, '');
    }

    public function withdraw($amount)
    {
        if ($_SESSION['balance'] >= $amount) {
            $_SESSION['balance'] -= $amount;
            $this->balance = $_SESSION['balance'];
            $this->addTransaction('Withdraw', '', $amount);
        } else {
            echo "Your balance is insufficient<br>";
        }
    }

    public function transfer($recipient, $amount)
    {
        // Check if the recipient's user data file exists
        $recipientDataFile = $this->userDataDirectory . $recipient . '.json';
        if (file_exists($recipientDataFile)) {
            $recipientData = json_decode(file_get_contents($recipientDataFile), true);
            if ($recipient !== $this->username) {
                if ($_SESSION['balance'] >= $amount) {
                    $_SESSION['balance'] -= $amount;
                    $this->balance = $_SESSION['balance'];
                    $this->addTransaction('Transfer', '', $amount, "Transfer to $recipient");

                    $recipientData['balance'] += $amount;
                    $recipientData['transactions'][] = [
                        'time' => date('Y-m-d H:i:s'),
                        'type' => 'Transfer',
                        'debit' => '',
                        'credit' => $amount,
                        'balance' => $recipientData['balance'],
                        'description' => "Transfer from " . $this->username,
                    ];
                    file_put_contents($recipientDataFile, json_encode($recipientData));
                } else {
                    echo "Your balance is insufficient for the transfer<br>";
                }
            } else {
                echo "You cannot transfer funds to yourself<br>";
            }
        } else {
            echo "Recipient not found<br>";
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    private function loadUserData()
    {
        $userDataFile = $this->userDataDirectory . $this->username . '.json';
        $userData = json_decode(file_get_contents($userDataFile), true);
        $this->balance = $userData['balance'];
        $this->transactions = $userData['transactions'];
    }

    public function addTransaction($type, $debit, $credit, $description = '')
    {
        $userDataDirectory = 'internal/user_data/';
        $_SESSION['transactions'][] = [
            'time' => date('Y-m-d H:i:s'),
            'type' => $type,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $_SESSION['balance'],
            'description' => $description,
        ];
        $this->transactions = $_SESSION['transactions'];

        $userDataFile = $userDataDirectory . $this->getUsername() . '.json';
        $userData = json_decode(file_get_contents($userDataFile), true);
        $userData['balance'] = $this->balance;
        $userData['transactions'] = $this->transactions;
        file_put_contents($userDataFile, json_encode($userData));
    }

    public function getBalance()
    {
        return $_SESSION['balance'];
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }
}

?>
