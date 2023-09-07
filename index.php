<?php
session_start();

// Directory to store user data files
$userDataDirectory = 'internal/user_data/';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

        if ($action === 'deposit') {
            $_SESSION['balance'] += $amount;
            $_SESSION['transactions'][] = [
                'time' => date('Y-m-d H:i:s'),
                'type' => 'Deposit',
                'debit' => $amount,
                'credit' => '',
                'balance' => $_SESSION['balance'],
                'description' => '',
            ];
        } elseif ($action === 'withdraw') {
            if ($_SESSION['balance'] >= $amount) {
                $_SESSION['balance'] -= $amount;
                $_SESSION['transactions'][] = [
                    'time' => date('Y-m-d H:i:s'),
                    'type' => 'Withdraw',
                    'debit' => '',
                    'credit' => $amount,
                    'balance' => $_SESSION['balance'],
                    'description' => '',
                ];
            } else {
                echo "Your balance is insufficient<br>";
            }
        } elseif ($action === 'transfer') {
            $recipient = $_POST['recipient'];
            $recipient_amount = floatval($_POST['recipient_amount']); 

            // Check if the recipient's user data file exists
            $recipientDataFile = $userDataDirectory . $recipient . '.json';
            if (file_exists($recipientDataFile)) {
                $recipientData = json_decode(file_get_contents($recipientDataFile), true);
                if ($recipient !== $_SESSION['user']) {
                    // Transfer funds if the recipient exists and is not the sender
                    if ($_SESSION['balance'] >= $recipient_amount) {
                        $_SESSION['balance'] -= $recipient_amount;
                        $_SESSION['transactions'][] = [
                            'time' => date('Y-m-d H:i:s'),
                            'type' => 'Transfer',
                            'debit' => '',
                            'credit' => $recipient_amount,
                            'balance' => $_SESSION['balance'],
                            'description' => "Transfer to $recipient",
                        ];

                        $recipientData['balance'] += $recipient_amount;
                        $recipientData['transactions'][] = [
                            'time' => date('Y-m-d H:i:s'),
                            'type' => 'Transfer',
                            'debit' => $recipient_amount,
                            'credit' => '',
                            'balance' => $recipientData['balance'],
                            'description' => "Transfer from " . $_SESSION['user'],
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
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Retrieve user-specific financial data from the session
$user_balance = $_SESSION['balance'];
$user_username = $_SESSION['user'];

// Store the transaction data in the user's JSON file
if (isset($_POST['action']) && in_array($_POST['action'], ['deposit', 'withdraw', 'transfer'])) {
    $transactionData = [
        'time' => date('Y-m-d H:i:s'),
        'type' => $_POST['action'],
        'debit' => $_POST['action'] === 'deposit' ? $_POST['amount'] : '',
        'credit' => $_POST['action'] === 'withdraw' ? $_POST['amount'] : '',
        'balance' => $user_balance,
        'description' => $_POST['action'] === 'transfer' ? "Transfer to " . $_POST['recipient'] : '',
    ];

    // Update the user's JSON file with the new transactions
    $userDataFile = $userDataDirectory . $user_username . '.json';
    $userData = json_decode(file_get_contents($userDataFile), true);
    $userData['balance'] = $user_balance;
    $userData['transactions'][] = $transactionData;
    file_put_contents($userDataFile, json_encode($userData));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Financial Statement</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }
    
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Financial Statement</h1>
    <h2>Welcome, <?php echo $_SESSION['user']; ?></h2>
    <p>Balance: <?php echo $_SESSION['balance']; ?></p>

    <form method="post">
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount">
        <input type="submit" name="action" value="deposit">
        <input type="submit" name="action" value="withdraw">
    </form>

    <h2>Transaction History</h2>
    <table>
        <tr>
            <th>Time</th>
            <th>Type</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
            <th>Description</th>
        </tr>
        <?php foreach ($_SESSION['transactions'] as $transaction) { ?>
            <tr>
                <td><?php echo $transaction['time']; ?></td>
                <td><?php echo $transaction['type']; ?></td>
                <td><?php echo $transaction['debit']; ?></td>
                <td><?php echo $transaction['credit']; ?></td>
                <td><?php echo $transaction['balance']; ?></td>
                <td><?php echo $transaction['description']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>Transfer Funds</h2>
    <form method="post">
        <label for="recipient">Recipient:</label>
        <input type="text" id="recipient" name="recipient">
        <label for="transfer_ammount">Transfer Ammount:</label>
        <input type="text" id="recipient_amount" name="recipient_amount">
        <input type="submit" name="action" value="transfer">
    </form>

    <form method="post">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>
