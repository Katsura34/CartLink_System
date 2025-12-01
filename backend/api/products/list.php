<?php
/**
 * Products API - Get All Products
 * GET /backend/api/products/list.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed', null, 405);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get query parameters
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : 'active';
    
    // Build query
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE 1=1";
    
    if ($status) {
        $query .= " AND p.status = :status";
    }
    
    if ($category_id) {
        $query .= " AND p.category_id = :category_id";
    }
    
    if ($search) {
        $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
    }
    
    $query .= " ORDER BY p.created_at DESC";
    
    $stmt = $db->prepare($query);
    
    if ($status) {
        $stmt->bindParam(':status', $status);
    }
    
    if ($category_id) {
        $stmt->bindParam(':category_id', $category_id);
    }
    
    if ($search) {
        $searchTerm = "%{$search}%";
        $stmt->bindParam(':search', $searchTerm);
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(true, 'Products retrieved successfully', $products);
} catch(Exception $e) {
    sendResponse(false, 'Failed to retrieve products: ' . $e->getMessage(), null, 500);
}
?>
