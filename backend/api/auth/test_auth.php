<?php
/**
 * Test Authentication Header Detection
 * GET/POST /backend/api/auth/test_auth.php
 * This endpoint helps debug authorization header issues
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

// Get authentication token
$token = getAuthToken();

// Get authentication result
$auth = verifyAuth();

// Prepare detailed debug information
$debugInfo = [
    'token_found' => !empty($token),
    'token_length' => $token ? strlen($token) : 0,
    'token_format_valid' => $token ? (count(explode('.', $token)) === 3) : false,
    'auth_valid' => $auth !== false,
    'auth_data' => $auth ? $auth : null,
    'server_vars' => [
        'HTTP_AUTHORIZATION' => isset($_SERVER['HTTP_AUTHORIZATION']) ? 'Set' : 'Not set',
        'REDIRECT_HTTP_AUTHORIZATION' => isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ? 'Set' : 'Not set',
        'PHP_AUTH_BEARER' => isset($_SERVER['PHP_AUTH_BEARER']) ? 'Set' : 'Not set'
    ]
];

// Add getallheaders info if available
if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $debugInfo['getallheaders'] = [
        'Authorization' => isset($headers['Authorization']) ? 'Set' : 'Not set',
        'authorization' => isset($headers['authorization']) ? 'Set' : 'Not set'
    ];
}

// Add apache_request_headers info if available
if (function_exists('apache_request_headers')) {
    $headers = apache_request_headers();
    $debugInfo['apache_request_headers'] = [
        'Authorization' => isset($headers['Authorization']) ? 'Set' : 'Not set',
        'authorization' => isset($headers['authorization']) ? 'Set' : 'Not set'
    ];
}

if ($auth) {
    sendResponse(true, 'Authentication successful', $debugInfo);
} else {
    http_response_code(200); // Keep 200 for debugging
    echo json_encode([
        'success' => false,
        'message' => 'Authentication failed or no token provided',
        'data' => $debugInfo
    ]);
}
?>
