<?php
/**
 * Authentication API - Login Endpoint
 * POST /backend/api/auth/login.php
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

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Validate JSON decoding
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Invalid JSON data', null, 400);
}

// Validate input
if (empty($data->email) || empty($data->password)) {
    sendResponse(false, 'Email and password are required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Query for user
    $query = "SELECT id, username, email, password, full_name, role FROM users WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $data->email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password
        if (verifyPassword($data->password, $user['password'])) {
            // Generate token
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'exp' => time() + (60 * 60 * 24) // 24 hours
            ];
            
            $token = generateToken($payload);
            
            // Return user data without password
            unset($user['password']);
            
            sendResponse(true, 'Login successful', [
                'token' => $token,
                'user' => $user
            ]);
        } else {
            sendResponse(false, 'Invalid credentials', null, 401);
        }
    } else {
        sendResponse(false, 'Invalid credentials', null, 401);
    }
} catch(Exception $e) {
    sendResponse(false, 'Login failed: ' . $e->getMessage(), null, 500);
}
?>
