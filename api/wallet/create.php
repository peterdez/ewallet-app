<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate wallet object
include_once '../objects/wallet.php';
  
$database = new Database();
$db = $database->getConnection();
  
$wallet = new Wallet($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->balance) &&
    !empty($data->type_id) &&
    !empty($data->user_id)
){
  
    // set wallet property values
    $wallet->balance = $data->balance;
    $wallet->type_id = $data->type_id;
    $wallet->user_id = $data->user_id;
    $wallet->created = date('Y-m-d H:i:s');
  
    // create the wallet
    if($wallet->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Wallet was created."));
    }
  
    // if unable to create the wallet, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create wallet."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create wallet. Data is incomplete."));
}
?>