<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// http://192.168.1.28:8686/register.php
include_once './connection.php';
// đăng ký
try {
    $decodedData = stripslashes(file_get_contents("php://input"));
    $data = json_decode($decodedData);

    if ($data === null  || !isset($data->name) || !isset($data->password) || !isset($data->password_confirm)) {
        echo json_encode(
            // || !isset($data->name)
            array(
                "status" => false,
                "message" => "Invalid JSON data"
            )
        );
        exit;
    }

    // kiểm tra name đã tồn tại chưa
    $sqlQuery = "SELECT * FROM users WHERE name = :name";
    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo json_encode(
            array(
                "status" => false,
                "message" => "Username already exists"
            )
        );
        return;
    }

    // thêm dữ liệu vào db
    // $name = $data->name;
    $name = $data->name;
    $password = $data->password;
    $password_confirm = $data->password_confirm;
    $otp = $data->otp;

    // so sánh password và confirm password
    if ($password != $password_confirm) {
        echo json_encode(
            array(
                "status" => false,
                "message" => "Passwords do not match"
            )
        );
        return;
    }

    // kiểm tra OTP và name nếu trùng tiếp tục đăng ký
    $sqlQuery = "SELECT * FROM users WHERE otp = :otp";
    // name = :name AND
    $stmt = $dbConn->prepare($sqlQuery);
    // $stmt->bindParam(':name', $data->name, PDO::PARAM_STR);
    $stmt->bindParam(':otp', $data->otp, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Nếu đã xác nhận, tiếp tục với cập nhật thông tin
        $sqlUpdate = "UPDATE users SET password = :password, name = :name WHERE otp = :otp";
        // name = :name AND 
        $stmtUpdate = $dbConn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':password', $data->password, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':name', $data->name, PDO::PARAM_STR);
        // $stmtUpdate->bindParam(':name', $data->name, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':otp', $data->otp, PDO::PARAM_STR);
        $stmtUpdate->execute();

        echo json_encode(
            array(
                "status" => true,
                "message" => "Registration successful"
            )
        );
    } else {
        // Nếu chưa xác nhận, thông báo lỗi
        echo json_encode(
            array(
                "status" => false,
                "message" => "OTP not confirmed"
            )
        );
    }

} catch (Exception $e) {
    echo json_encode(
        array(
            "status" => false,
            "message" => $e->getMessage()
        )
    );
}
?>
