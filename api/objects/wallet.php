<?php
class Wallet{
  
    // database connection and table name
    private $conn;
    private $table_name = "wallets";
  
    // object properties
    public $id;
    public $type_id;
    public $type_name;
    public $minbalance;
    public $user_id;
    public $money_sent;
    public $balance;
    public $receiver_wallet_id;
    public $wallet_owner;
    public $created;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read wallets
    function index(){
    
        // select all query
        $query = "SELECT
                    t.name as type_name, w.id, w.balance, w.type_id, w.user_id, w.created
                FROM
                    " . $this->table_name . " w
                    LEFT JOIN
                        types t
                            ON w.type_id = t.id
                ORDER BY
                    w.created DESC";

        //$query = "SELECT id, balance, type_id FROM wallets";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create wallet
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    balance=:balance, type_id=:type_id, user_id=:user_id, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->balance=htmlspecialchars(strip_tags($this->balance));
        $this->type_id=htmlspecialchars(strip_tags($this->type_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":balance", $this->balance);
        $stmt->bindParam(":type_id", $this->type_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":created", $this->created);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // used when filling up the update wallet form
    function show(){
    
        // query to read single record
        $query = "SELECT
                    t.name as type_name, t.min_balance as minbalance , w.id, w.balance, w.type_id, w.user_id, w.created, u.name AS username
                FROM
                    " . $this->table_name . " w
                    LEFT JOIN
                        types t
                            ON w.type_id = t.id
                    LEFT JOIN
                    users u
                        ON w.user_id = u.id
                WHERE
                    w.id = ?
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
        $this->balance = $row['balance'];
        $this->type_id = $row['type_id'];
        $this->type_name = $row['type_name'];
        $this->minbalance = $row['minbalance'];
        $this->wallet_owner = $row['username'];
        return $row;
    }

    // update the wallet
    function sendMoney(){
         $wallet_row = $this->show();

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    balance = :balance
                WHERE
                    id = :id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->money_sent=htmlspecialchars(strip_tags($this->money_sent));
        $new_balance = $wallet_row['balance'] - $this->money_sent;
        if($wallet_row['minbalance'] > $new_balance) {
             return false;
        }
        //$this->balance=htmlspecialchars(strip_tags($this->balance));
    
        // bind new values
        $stmt->bindParam(':balance', $new_balance);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            $this->updateReceiverWallet($this->receiver_wallet_id, $this->money_sent);
            $this->createTransaction($this->money_sent, $this->id, $this->receiver_wallet_id, $wallet_row['user_id']);
            return true;
        }
    
        return false;
    }

    // create wallet
    function createTransaction($amount, $from_wallet_id, $to_wallet_id, $from_user_id){
    
        // query to insert record
        $query = "INSERT INTO transactions SET
                    amount=:amount, from_wallet_id=:from_wallet_id, to_wallet_id=:to_wallet_id, from_user_id=:from_user_id, created=:created";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        /*$this->balance=htmlspecialchars(strip_tags($this->balance));
        $this->type_id=htmlspecialchars(strip_tags($this->type_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->created=htmlspecialchars(strip_tags($this->created));*/
        $date = date('Y-m-d H:i:s');
        // bind values
        $stmt->bindParam(":amount", $amount);
        //$stmt->bindParam(":amount", $this->type_id);
        $stmt->bindParam(":from_wallet_id", $from_wallet_id);
        $stmt->bindParam(":to_wallet_id", $to_wallet_id);
        $stmt->bindParam(":from_user_id", $from_user_id);
        $stmt->bindParam(":created", $date);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // used when filling up the update wallet form
    function readReceiverWallet($receiver_wallet_id){
    
        // query to read single record
        $query = "SELECT
                    t.name as type_name, w.id, w.balance, w.type_id, w.user_id, w.created
                FROM
                    " . $this->table_name . " w
                    LEFT JOIN
                        types t
                            ON w.type_id = t.id
                WHERE
                    w.id = ?
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        $receiver_wallet_id=htmlspecialchars(strip_tags($receiver_wallet_id));
    
        // bind id of wallet to be updated
        $stmt->bindParam(1, $receiver_wallet_id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['balance'];
    }

    // update the wallet
    function updateReceiverWallet($receiver_wallet_id, $money_sent){
        $balance = $this->readReceiverWallet($receiver_wallet_id);

       // update query
       $query = "UPDATE
                   " . $this->table_name . "
               SET
                   balance = :balance
               WHERE
                   id = :id";
   
       // prepare query statement
       $stmt = $this->conn->prepare($query);
   
       // sanitize
       $money_sent=htmlspecialchars(strip_tags($money_sent));
       $receiver_wallet_id=htmlspecialchars(strip_tags($receiver_wallet_id));
       $new_balance = $balance + $money_sent;
       //$this->balance=htmlspecialchars(strip_tags($this->balance));
   
       // bind new values
       $stmt->bindParam(':balance', $new_balance);
       $stmt->bindParam(':id', $receiver_wallet_id);
   
       // execute the query
       if($stmt->execute()){
           return true;
       }
   
       return false;
   }
}
?>