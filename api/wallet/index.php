<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/wallet.php';
  
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$wallet = new Wallet($db);
  
// query wallets
$stmt = $wallet->index();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // wallets array
    $wallets_arr=array();
    $wallets_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $wallet_item=array(
            "id" => $id,
            "balance" => $balance,
            "type_id" => $type_id,
            "type_name" => $type_name,
            "user_id" => $user_id,
        );
  
        array_push($wallets_arr["records"], $wallet_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($wallets_arr);
}
  
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No wallets found.")
    );
}