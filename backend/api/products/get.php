<?php
/**
 * Products API - Get Single Product
 * GET /backend/api/products/get.php?id=1
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed', null, 405);
}

if (!isset($_GET['id'])) {
    sendResponse(false, 'Product ID is required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        sendResponse(true, 'Product retrieved successfully', $product);
    } else {
        sendResponse(false, 'Product not found', null, 404);
    }
} catch(Exception $e) {
    sendResponse(false, 'Failed to retrieve product: ' . $e->getMessage(), null, 500);
}
?>
