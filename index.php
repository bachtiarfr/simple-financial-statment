<?php
session_start();

require_once 'classes/User.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = unserialize($_SESSION['user']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

        switch ($action) {
            case 'deposit':
                $user->deposit($amount);
                break;
            case 'withdraw':
                $user->withdraw($amount);
                break;
            case 'transfer':
                $recipient = $_POST['recipient'];
                $recipientAmount = floatval($_POST['recipient_amount']);
                $user->transfer($recipient, $recipientAmount);
                break;
        }
    }

    if (isset($_POST['logout'])) {
        $user->logout();
    }
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
    <h2>Welcome, <?php echo $user->getUsername(); ?></h2>
    <p>Balance: <?php echo $user->getBalance(); ?></p>

    <form method="post">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount">
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
        <?php foreach ($user->getTransactions() as $transaction) { ?>
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
        <label for="recipient_amount">Transfer Amount:</label>
        <input type="number" id="recipient_amount" name="recipient_amount">
        <input type="submit" name="action" value="transfer">
    </form>

    <form method="post">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>
