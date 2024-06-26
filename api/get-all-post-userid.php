<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './connection.php';

try {
    // Nhận dữ liệu từ yêu cầu
    $data = json_decode(file_get_contents("php://input"));

    // Kiểm tra xem có userid được gửi hay không
    if (!isset($data->userid)) {
        http_response_code(400);
        echo json_encode(array('status' => false, 'message' => 'Thiếu tham số userid'));
        exit;
    }

    $userid = $data->userid;

    $postQuery = "SELECT ID,userid,IMAGE, AVATAR,TIME,NAME,CONTENT,LIKES FROM posts WHERE userid = :userid
                  ORDER BY posts.time DESC";
    $postStmt = $dbConn->prepare($postQuery);
    $postStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $postStmt->execute();
    
    $posts = $postStmt->fetchAll(PDO::FETCH_ASSOC);
    $postCount = count($posts);

    echo json_encode(array('status' => true, 'postCount' => $postCount ,'posts' => $posts));
} catch (Exception $e) {
    echo json_encode(array('status' => false, 'message' => $e->getMessage()));
}
?>
