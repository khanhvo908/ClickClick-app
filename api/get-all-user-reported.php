<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database connection
include_once './connection.php';

try {
    // Query to get users with reported posts and count of reported posts
    $query = "
        SELECT 
            p.USERID, 
            u.Available,
            COUNT(*) AS reported_count
        FROM 
            POSTS p
        LEFT JOIN
            USERS u ON p.USERID = u.ID
        WHERE 
            p.AVAILABLE = 0
        GROUP BY 
            p.USERID
        ORDER BY 
            reported_count DESC
    ";

    // Prepare and execute the query
    $stmt = $dbConn->prepare($query);
    $stmt->execute();

    // Fetch the results
    $reportedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results in JSON format
    echo json_encode(array(
        "status" => true,
        "msg" => "Success",
        "reported_users" => $reportedUsers
    ));
} catch (PDOException $e) {
    // Handle errors gracefully
    http_response_code(500); // Internal Server Error
    echo json_encode(array(
        "status" => false,
        "msg" => "Unable to fetch reported users: " . $e->getMessage()
    ));
}
?>
