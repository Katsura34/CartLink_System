<?php
/**
 * Products API - Update Product (Admin Only)
 * PUT /backend/api/products/update.php
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

if (empty($data->id)) {
    sendResponse(false, 'Product ID is required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE products SET 
              category_id = :category_id,
              name = :name,
              description = :description,
              price = :price,
              stock = :stock,
              image_url = :image_url,
              status = :status
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $data->id);
    $stmt->bindParam(':category_id', $data->category_id);
    $stmt->bindParam(':name', $data->name);
    $stmt->bindParam(':description', $data->description);
    $stmt->bindParam(':price', $data->price);
    $stmt->bindParam(':stock', $data->stock);
    $stmt->bindParam(':image_url', $data->image_url);
    $stmt->bindParam(':status', $data->status);
    
    if ($stmt->execute()) {
        sendResponse(true, 'Product updated successfully');
    } else {
        sendResponse(false, 'Failed to update product', null, 500);
    }
} catch(Exception $e) {
    sendResponse(false, 'Failed to update product: ' . $e->getMessage(), null, 500);
}
?>
