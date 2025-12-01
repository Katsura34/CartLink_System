<?php
/**
 * Orders API - Update Order Status (Admin Only)
 * PUT /backend/api/orders/update_status.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

// Verify authentication
$auth = verifyAuth();
if (!$auth || $auth['role'] !== 'admin') {
    sendResponse(false, 'Unauthorized access', null, 401);
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->order_id) || empty($data->status)) {
    sendResponse(false, 'Order ID and status are required', null, 400);
}

$validStatuses = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'completed', 'cancelled'];
if (!in_array($data->status, $validStatuses)) {
    sendResponse(false, 'Invalid status', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // If cancelling, restore stock
    if ($data->status === 'cancelled') {
        $db->beginTransaction();
        
        // Get order items
        $query = "SELECT product_id, quantity FROM order_items WHERE order_id = :order_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_id', $data->order_id);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Restore stock
        foreach ($items as $item) {
            $query = "UPDATE products SET stock = stock + :quantity WHERE id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':product_id', $item['product_id']);
            $stmt->execute();
        }
    }
    
    // Update order status
    $query = "UPDATE orders SET status = :status WHERE id = :order_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $data->status);
    $stmt->bindParam(':order_id', $data->order_id);
    $stmt->execute();
    
    if ($data->status === 'cancelled') {
        $db->commit();
    }
    
    sendResponse(true, 'Order status updated successfully');
} catch(Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    sendResponse(false, 'Failed to update order status: ' . $e->getMessage(), null, 500);
}
?>
