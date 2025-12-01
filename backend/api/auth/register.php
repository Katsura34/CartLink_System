<?php
/**
 * Authentication API - Register Endpoint
 * POST /backend/api/auth/register.php
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

// Validate input
if (empty($data->username) || empty($data->email) || empty($data->password) || empty($data->full_name)) {
    sendResponse(false, 'All fields are required', null, 400);
}

// Validate email format
if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Invalid email format', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if user already exists
    $checkQuery = "SELECT id FROM users WHERE email = :email OR username = :username";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':email', $data->email);
    $checkStmt->bindParam(':username', $data->username);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        sendResponse(false, 'User with this email or username already exists', null, 409);
    }
    
    // Hash password
    $hashedPassword = hashPassword($data->password);
    
    // Insert new user
    $query = "INSERT INTO users (username, email, password, full_name, phone, address, role) 
              VALUES (:username, :email, :password, :full_name, :phone, :address, 'customer')";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $data->username);
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':full_name', $data->full_name);
    
    $phone = $data->phone ?? null;
    $address = $data->address ?? null;
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    
    if ($stmt->execute()) {
        $userId = $db->lastInsertId();
        
        // Generate token
        $payload = [
            'user_id' => $userId,
            'email' => $data->email,
            'role' => 'customer',
            'exp' => time() + (60 * 60 * 24) // 24 hours
        ];
        
        $token = generateToken($payload);
        
        sendResponse(true, 'Registration successful', [
            'token' => $token,
            'user' => [
                'id' => $userId,
                'username' => $data->username,
                'email' => $data->email,
                'full_name' => $data->full_name,
                'role' => 'customer'
            ]
        ], 201);
    } else {
        sendResponse(false, 'Registration failed', null, 500);
    }
} catch(Exception $e) {
    sendResponse(false, 'Registration failed: ' . $e->getMessage(), null, 500);
}
?>
