<?php
/**
 * Orders API - Get Order Details
 * GET /backend/api/orders/get.php?id=1
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

if (!isset($_GET['id'])) {
    sendResponse(false, 'Order ID is required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get order
    $query = "SELECT o.*, u.full_name as customer_name, u.email as customer_email 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id = :id";
    
    // If not admin, restrict to user's own orders
    if ($auth['role'] !== 'admin') {
        $query .= " AND o.user_id = :user_id";
    }
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    
    if ($auth['role'] !== 'admin') {
        $stmt->bindParam(':user_id', $auth['user_id']);
    }
    
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        sendResponse(false, 'Order not found', null, 404);
    }
    
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get order items
    $query = "SELECT * FROM order_items WHERE order_id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order_id', $_GET['id']);
    $stmt->execute();
    
    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(true, 'Order retrieved successfully', $order);
} catch(Exception $e) {
    sendResponse(false, 'Failed to retrieve order: ' . $e->getMessage(), null, 500);
}
?>
