<?php

class FinancialStatement {
    private $users = [];

    public function addUser($user) {
        $this->users[$user->getUsername()] = $user;
    }

    public function getUser($username) {
        return $username;
    }

    public function saveUserTransactions($username) {
        $user = $this->getUser($username);
        if ($user) {
            $userData = [
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'balance' => $user->getBalance(),
                'transactions' => $user->getTransactions(),
            ];
            $userDataFile = 'user_data/' . $username . '.json';
            file_put_contents($userDataFile, json_encode($userData));
        }
    }
}
