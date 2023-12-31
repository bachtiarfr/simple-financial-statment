# Simple Financial Program

This project is a simple financial program built using PHP native with Object-Oriented Programming (OOP) principles. It adheres to the provided backend specifications and does not use any external links or assets outside of the project folder.

## Features

- Deposit funds
- Withdraw funds
- Check balance
- Transaction history
- Multi-user support with login
- Session storage
- No external database, using JSON files for user data include the transaction data

## Usage

1. Clone or download the project to your local environment.

2. Run the `generate_user_data.php` script to create user data files:
   - Example URL: `{url}/generate_user_data.php`

3. Start the program by opening `index.php` in your web browser.

4. Log in with the provided users or create your own:
   - User: Feon, Password: password1
   - User: Vira, Password: password2

5. Use the program to deposit, withdraw, check balances, and transfer funds between users.

## Project Structure

- `index.php`: The main application file with the user interface and financial functionalities.
- `login.php`: Handles user login and redirects to `index.php` upon successful login.
- `generate_user_data.php`: Generates user data JSON files for login and transactions.
- `internal/user_data/`: Directory to store user data JSON files.
- `classes/`: Contains the class definitions for `User` and `FinancialStatement`.

## Requirements

- PHP >= 8.0

## Notes
- This project adheres to the backend specifications provided, which do not require the use of a database. Instead, it utilizes session storage and multi-dimensional arrays to manage user data and transactions.
- User data and transaction records are not stored in a traditional database but are managed within the PHP session and stored as JSON files in the `internal/user_data/` directory. This approach ensures simplicity and eliminates the need for external database dependencies.
- Transactions are recorded in real-time by updating the user's session data and corresponding JSON files. This design choice aligns with the project's goal of maintaining a lightweight and self-contained financial program.

