<?php
/**
 * Products API - Create Product (Admin Only)
 * POST /backend/api/products/create.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

// Verify authentication
$auth = verifyAuth();
if (!$auth || $auth['role'] !== 'admin') {
    sendResponse(false, 'Unauthorized access', null, 401);
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->price)) {
    sendResponse(false, 'Name and price are required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO products (category_id, name, description, price, stock, image_url, status) 
              VALUES (:category_id, :name, :description, :price, :stock, :image_url, :status)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':category_id', $data->category_id);
    $stmt->bindParam(':name', $data->name);
    $stmt->bindParam(':description', $data->description);
    $stmt->bindParam(':price', $data->price);
    
    $stock = $data->stock ?? 0;
    $image_url = $data->image_url ?? null;
    $status = $data->status ?? 'active';
    
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':image_url', $image_url);
    $stmt->bindParam(':status', $status);
    
    if ($stmt->execute()) {
        sendResponse(true, 'Product created successfully', ['id' => $db->lastInsertId()], 201);
    } else {
        sendResponse(false, 'Failed to create product', null, 500);
    }
} catch(Exception $e) {
    sendResponse(false, 'Failed to create product: ' . $e->getMessage(), null, 500);
}
?>
