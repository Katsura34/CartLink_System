<?php
/**
 * Orders API - Get User Orders
 * GET /backend/api/orders/list.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed', null, 405);
}

// Verify authentication
$auth = verifyAuth();
if (!$auth) {
    sendResponse(false, 'Unauthorized access', null, 401);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // If admin, get all orders; otherwise, get user's orders
    if ($auth['role'] === 'admin') {
        $query = "SELECT o.*, u.full_name as customer_name, u.email as customer_email 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  ORDER BY o.created_at DESC";
        $stmt = $db->prepare($query);
    } else {
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $auth['user_id']);
    }
    
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(true, 'Orders retrieved successfully', $orders);
} catch(Exception $e) {
    sendResponse(false, 'Failed to retrieve orders: ' . $e->getMessage(), null, 500);
}
?>
