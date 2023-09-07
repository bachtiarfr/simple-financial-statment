<?php
// Directory to store user data files
$userDataDirectory = 'internal/user_data/';

// Define user data for Feon and Vira
$users = [
    'Feon' => [
        'password' => 'password1',
        'balance' => 0,
        'transactions' => [],
    ],
    'Vira' => [
        'password' => 'password2',
        'balance' => 0,
        'transactions' => [],
    ],
];

// Create user data files
foreach ($users as $username => $userData) {
    $userDataFile = $userDataDirectory . $username . '.json';
    file_put_contents($userDataFile, json_encode($userData));
}

// Redirect to the login page after 3 seconds
header("refresh:3;url=login.php");
echo "User data files created successfully. Redirecting to login...";
?>
