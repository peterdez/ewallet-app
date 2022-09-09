<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "users";
  
    // object properties
    public $id;
    public $name;
    public $email;
    public $own_wallets;
    public $own_transactions;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read wallets
    function read(){
    
        // select all query

        $query = "SELECT id, name, email FROM users";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // used when filling up the update wallet form
    function readOne(){
    
        // query to read single record
        /*$query = "SELECT
                    w.balance as balance, u.id, u.name, u.email, t.amount AS amount
                FROM
                    " . $this->table_name . " u
                    LEFT JOIN
                        wallets w
                            ON u.id = w.user_id
                    LEFT JOIN
                    transactions t
                        ON u.id = t.from_user_id
                WHERE
                    u.id = ?
                LIMIT
                    0,1";*/
        $query = "SELECT id, name, email FROM " . $this->table_name . " WHERE
        id = ?
        LIMIT
        0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of wallet to be updated
        $stmt->bindParam(1, $this->id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        //$this->wallet_balance = $row['balance'];
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $own_wallets = $this->getUserWallets($this->id);
        $own_transactions = $this->getUserTransactions($this->id);
        $this->own_wallets = $own_wallets;
        $this->own_transactions = $own_transactions;

    }

    public function getUserWallets($user_id) {
        $query = "SELECT
                    t.name as type_name, w.id, w.balance, w.type_id, w.user_id, w.created
                FROM
                    wallets w
                    LEFT JOIN
                        types t
                            ON w.type_id = t.id
                WHERE
                    w.user_id = ?";
        //$query = "SELECT balance, type_id FROM wallets WHERE user_id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of wallet to be updated
        $stmt->bindParam(1, $user_id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $own_wallets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $own_wallets;
    }
    
    
    public function getUserTransactions($user_id) {
        
        $query = "SELECT id, amount FROM transactions WHERE from_user_id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of wallet to be updated
        $stmt->bindParam(1, $user_id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $own_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $own_transactions;
    }
}
?>