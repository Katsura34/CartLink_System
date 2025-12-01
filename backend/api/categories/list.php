<?php
/**
 * Categories API - List All Categories
 * GET /backend/api/categories/list.php
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
    
    $query = "SELECT * FROM categories ORDER BY name ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    sendResponse(true, 'Categories retrieved successfully', $categories);
} catch(Exception $e) {
    sendResponse(false, 'Failed to retrieve categories: ' . $e->getMessage(), null, 500);
}
?>
