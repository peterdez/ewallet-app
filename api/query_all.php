<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wallets_db";


   // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT
  (SELECT COUNT(*) FROM users) as usersTableCount, 
  (SELECT COUNT(*) FROM wallets) as walletstableCount,
  (SELECT COUNT(*) FROM transactions) as transactionsTableCount,
  (SELECT SUM(balance) FROM wallets) as totalWalletBalance";
//$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

if($data){
  
    // users array
    $total_records_arr=array();
    $total_records_arr["records"]=$data;
    
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($total_records_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No records found.")
    );
}

?>