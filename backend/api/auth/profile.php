<?php
/**
 * Authentication API - Get User Profile
 * GET /backend/api/auth/profile.php
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

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get user profile
    $query = "SELECT id, username, email, full_name, phone, address, role, created_at 
              FROM users WHERE id = :user_id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $auth['user_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        sendResponse(true, 'Profile retrieved successfully', $user);
    } else {
        sendResponse(false, 'User not found', null, 404);
    }
} catch(Exception $e) {
    sendResponse(false, 'Failed to retrieve profile: ' . $e->getMessage(), null, 500);
}
?>
