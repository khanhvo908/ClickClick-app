<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Import connection.php và JWT
include_once './connection.php';
include_once './helpers/jwt.php';

try {
    // Nhận dữ liệu từ JSON
    if (!isset($_GET['postid'])) {
        http_response_code(400);
        echo json_encode(array('status' => false, 'message' => 'Thiếu tham số postid'));
        exit;
    }

    $postid = $_GET['postid'];

    // Kiểm tra bài viết trong bảng chats
    $sqlCheckChats = "SELECT * FROM chats WHERE postid = :postid";
    $stmtCheckChats = $dbConn->prepare($sqlCheckChats);
    $stmtCheckChats->bindParam(':postid', $postid, PDO::PARAM_INT);
    $stmtCheckChats->execute();

    // Nếu có tin nhắn liên quan trong bảng chats, xóa chúng
    if ($stmtCheckChats->rowCount() > 0) {
        $sqlDeleteChats = "DELETE FROM chats WHERE postid = :postid";
        $stmtDeleteChats = $dbConn->prepare($sqlDeleteChats);
        $stmtDeleteChats->bindParam(':postid', $postid, PDO::PARAM_INT);
        $stmtDeleteChats->execute();
    }

    // Kiểm tra bài viết trong bảng likes
    $sqlCheckLikes = "SELECT * FROM likes WHERE postid = :postid";
    $stmtCheckLikes = $dbConn->prepare($sqlCheckLikes);
    $stmtCheckLikes->bindParam(':postid', $postid, PDO::PARAM_INT);
    $stmtCheckLikes->execute();

    // Nếu có likes liên quan trong bảng likes, xóa chúng
    if ($stmtCheckLikes->rowCount() > 0) {
        $sqlDeleteLikes = "DELETE FROM likes WHERE postid = :postid";
        $stmtDeleteLikes = $dbConn->prepare($sqlDeleteLikes);
        $stmtDeleteLikes->bindParam(':postid', $postid, PDO::PARAM_INT);
        $stmtDeleteLikes->execute();
    }

    // Tiến hành xóa bài viết từ bảng posts
    $sqlDeletePost = "DELETE FROM posts WHERE ID = :postid";
    $stmtDeletePost = $dbConn->prepare($sqlDeletePost);
    $stmtDeletePost->bindParam(':postid', $postid, PDO::PARAM_INT);
    $stmtDeletePost->execute();

    if ($stmtDeletePost) {
        echo json_encode(array(
            "status" => true,
            "message" => "Bài viết đã được xóa thành công."
        ));
    } else {
        echo json_encode(array(
            "status" => false,
            "message" => "Không tìm thấy bài viết hoặc bài viết đã bị xóa."
        ));
    }
} catch (PDOException $e) {
    // Xử lý lỗi nếu có
    http_response_code(500);
    echo json_encode(array(
        "status" => false,
        "message" => "Không thể xóa bài viết: " . $e->getMessage()
    ));
}
?>
