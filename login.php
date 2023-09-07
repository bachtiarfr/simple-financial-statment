<?php
session_start();

// Directory to store user data files
$userDataDirectory = 'internal/user_data/';
require_once 'classes/User.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user data file exists
    $userDataFile = $userDataDirectory . $username . '.json';

    if (file_exists($userDataFile)) {
        $userData = json_decode(file_get_contents($userDataFile), true);

        if ($userData['password'] === $password) {
            $_SESSION['user'] = $username;
            $_SESSION['balance'] = $userData['balance'];
            $_SESSION['transactions'] = $userData['transactions'];

            $user = new User($username);
           
            $_SESSION['user'] = serialize($user);
            $_SESSION['username'] = $username;

            header('Location: index.php');
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>
