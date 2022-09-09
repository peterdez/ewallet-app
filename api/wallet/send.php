<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/wallet.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare wallet object
$wallet = new Wallet($db);
  
// get id of wallet to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of wallet to be edited
$wallet->id = $data->id;
$wallet->receiver_wallet_id= $data->receiver_wallet_id; 
$wallet->money_sent = $data->money_sent;

// set wallet property values
//$wallet->balance = $data->balance;
  
// update the wallet
if($wallet->sendMoney()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Wallet was updated."));
}
  
// if unable to update the wallet, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update wallet."));
}
?>