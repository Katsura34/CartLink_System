<?php
/**
 * Authentication API - Update User Profile
 * POST /backend/api/auth/update_profile.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Handle preflight requests
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
if (!$auth) {
    sendResponse(false, 'Unauthorized access', null, 401);
}

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Validate JSON decoding
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Invalid JSON data', null, 400);
}

// Validate input
if (empty($data->full_name)) {
    sendResponse(false, 'Full name is required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Update user profile
    $query = "UPDATE users 
              SET full_name = :full_name, 
                  phone = :phone, 
                  address = :address 
              WHERE id = :user_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':full_name', $data->full_name);
    
    $phone = $data->phone ?? null;
    $address = $data->address ?? null;
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':user_id', $auth['user_id']);
    
    if ($stmt->execute()) {
        // Get updated user data
        $query = "SELECT id, username, email, full_name, phone, address, role 
                  FROM users WHERE id = :user_id LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $auth['user_id']);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        sendResponse(true, 'Profile updated successfully', $user);
    } else {
        sendResponse(false, 'Failed to update profile', null, 500);
    }
} catch(Exception $e) {
    sendResponse(false, 'Profile update failed: ' . $e->getMessage(), null, 500);
}
?>
