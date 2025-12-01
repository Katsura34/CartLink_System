<?php
/**
 * Authentication API - Change Password
 * POST /backend/api/auth/change_password.php
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
if (empty($data->current_password) || empty($data->new_password)) {
    sendResponse(false, 'Current password and new password are required', null, 400);
}

// Validate password length and strength
if (strlen($data->new_password) < 8) {
    sendResponse(false, 'New password must be at least 8 characters', null, 400);
}

// Check for password complexity
$hasUpper = preg_match('/[A-Z]/', $data->new_password);
$hasLower = preg_match('/[a-z]/', $data->new_password);
$hasNumber = preg_match('/[0-9]/', $data->new_password);

if (!$hasUpper || !$hasLower || !$hasNumber) {
    sendResponse(false, 'Password must contain at least one uppercase letter, one lowercase letter, and one number', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get current password hash
    $query = "SELECT password FROM users WHERE id = :user_id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $auth['user_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        sendResponse(false, 'User not found', null, 404);
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verify current password
    if (!verifyPassword($data->current_password, $user['password'])) {
        sendResponse(false, 'Current password is incorrect', null, 401);
    }
    
    // Hash new password
    $newPasswordHash = hashPassword($data->new_password);
    
    // Update password
    $query = "UPDATE users SET password = :password WHERE id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':password', $newPasswordHash);
    $stmt->bindParam(':user_id', $auth['user_id']);
    
    if ($stmt->execute()) {
        sendResponse(true, 'Password changed successfully');
    } else {
        sendResponse(false, 'Failed to change password', null, 500);
    }
} catch(Exception $e) {
    sendResponse(false, 'Password change failed: ' . $e->getMessage(), null, 500);
}
?>
