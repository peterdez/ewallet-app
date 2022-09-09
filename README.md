# E-Wallet App
E-Wallet Application built with PHP in which users can have multiple wallets with each wallet type having its own name, 
minimum balance and monthly interest rate. Wallets are able to send and receive money from other wallets.

### Endpoints and actions:
- api/user/index.php - Gets all users in the system
- api/user/show.php?id={user-id} -  Gets a user’s detail including the wallets they own and the transaction history of that user.
- api/wallet/index.php - Gets all wallets in the system
- api/wallet/show.php?id={wallet-id} - Gets a wallet’s detail including its owner, type and the transaction history of that wallet.
- api/query_all.php - Gets the count of users, count of wallets, total wallet balance, total volume of transactions.

***SQL file included for database setup***
