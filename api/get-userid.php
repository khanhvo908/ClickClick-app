<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//http://127.0.0.1:8686/get-userid.php?id=1
// ltìm user theo id
include_once './connection.php';
$data = json_decode(file_get_contents("php://input"));
$id = $_GET['id'];
// đọc dữ liệu từ database 

 $postQuery = "SELECT * FROM users WHERE id = :id";
 $postStmt = $dbConn->prepare($postQuery);
 $postStmt->bindParam(':id', $id, PDO::PARAM_INT);
 $postStmt->execute();
 $user = $postStmt->fetch(PDO::FETCH_ASSOC);

    array(
        "status" => true,
        "user" => $user
    );

?>