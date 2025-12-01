<?php
/**
 * Products API - Delete Product (Admin Only)
 * DELETE /backend/api/products/delete.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

// Verify authentication
$auth = verifyAuth();
if (!$auth || $auth['role'] !== 'admin') {
    sendResponse(false, 'Unauthorized access', null, 401);
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    sendResponse(false, 'Product ID is required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "DELETE FROM products WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data->id);
    
    if ($stmt->execute()) {
        sendResponse(true, 'Product deleted successfully');
    } else {
        sendResponse(false, 'Failed to delete product', null, 500);
    }
} catch(Exception $e) {
    sendResponse(false, 'Failed to delete product: ' . $e->getMessage(), null, 500);
}
?>
