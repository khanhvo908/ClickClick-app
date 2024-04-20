<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Bao gồm kết nối cơ sở dữ liệu
include_once './connection.php';
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem tham số 'id' có tồn tại trong URL không
if (!isset($data->userid)) {
    http_response_code(400);
    echo json_encode(array('status' => false, 'message' => 'Thiếu tham số userid'));
    exit;
}

// Lấy id từ URL
$userid = $data->userid;

// Kiểm tra xem người dùng có phải là quản trị viên không
$sqlQuery = "SELECT role FROM users WHERE ID = :userid";
$stmt = $dbConn->prepare($sqlQuery);
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['role'] === 'admin') {
    // Nếu người dùng là quản trị viên, trả về thông báo lỗi
    http_response_code(403); // Forbidden
    echo json_encode(array("status" => false, "message" => "Không thể khóa tài khoản quản trị viên"));
    exit;
}

try {
    // Update user's availability in the database
    $userQuery = "UPDATE users SET AVAILABLE = 0 WHERE ID = :userid";
    $userStmt = $dbConn->prepare($userQuery);
    $userStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    // Check if any rows were affected

    echo json_encode(array("status" => true, "message" => "Khóa tài khoản người dùng thành công"));
} catch (PDOException $e) {
    http_response_code(404);
    echo json_encode(array("status" => false, "message" => "Không tìm thấy người dùng" . $e->getMessage()));
}
