<?php
/**
 * Utility Functions for CartLink System
 * Helper functions for common operations
 */

/**
 * Generate a unique reference number for orders
 * @return string
 */
function generateReferenceNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Hash password using bcrypt
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password against hash
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Sanitize input data
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Generate JWT token (simple implementation)
 * @param array $payload
 * @param string $secret
 * @return string
 */
function generateToken($payload, $secret = null) {
    if ($secret === null) {
        $secret = defined('JWT_SECRET_KEY') ? JWT_SECRET_KEY : 'cartlink_secret_key_2024';
    }
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode($payload);
    
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

/**
 * Verify JWT token
 * @param string $token
 * @param string $secret
 * @return array|false
 */
function verifyToken($token, $secret = null) {
    if ($secret === null) {
        $secret = defined('JWT_SECRET_KEY') ? JWT_SECRET_KEY : 'cartlink_secret_key_2024';
    }
    $parts = explode('.', $token);
    
    if (count($parts) !== 3) {
        return false;
    }
    
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
    
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    // Use hash_equals to prevent timing attacks
    if (!hash_equals($base64UrlSignature, $base64UrlSignatureCheck)) {
        return false;
    }
    
    // Validate base64 decoding
    $payloadDecoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlPayload));
    if ($payloadDecoded === false) {
        return false;
    }
    
    $payload = json_decode($payloadDecoded, true);
    
    // Validate JSON decoding
    if ($payload === null && json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }
    
    // Check token expiration
    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return false;
    }
    
    return $payload;
}

/**
 * Send JSON response
 * @param bool $success
 * @param string $message
 * @param mixed $data
 * @param int $statusCode
 */
function sendResponse($success, $message, $data = null, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

/**
 * Get authorization token from headers
 * @return string|null
 */
function getAuthToken() {
    // Method 1: Try getallheaders() first (Apache with mod_php)
    // Note: getallheaders() is an alias for apache_request_headers()
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        
        // Check both capitalized and lowercase versions
        foreach (['Authorization', 'authorization'] as $header) {
            if (isset($headers[$header])) {
                $matches = [];
                if (preg_match('/Bearer\s+(.*)$/i', $headers[$header], $matches)) {
                    return trim($matches[1]);
                }
            }
        }
    }
    
    // Method 2: Check $_SERVER['HTTP_AUTHORIZATION'] (standard CGI/FastCGI)
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $matches = [];
        if (preg_match('/Bearer\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return trim($matches[1]);
        }
    }
    
    // Method 3: Check for REDIRECT_HTTP_AUTHORIZATION (Apache with mod_rewrite)
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $matches = [];
        if (preg_match('/Bearer\s+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches)) {
            return trim($matches[1]);
        }
    }
    
    // Method 4: Check PHP_AUTH_BEARER if set directly
    if (isset($_SERVER['PHP_AUTH_BEARER'])) {
        return trim($_SERVER['PHP_AUTH_BEARER']);
    }
    
    // Method 5: Check for Authorization in environment variable (set by .htaccess)
    if (isset($_ENV['HTTP_AUTHORIZATION'])) {
        $matches = [];
        if (preg_match('/Bearer\s+(.*)$/i', $_ENV['HTTP_AUTHORIZATION'], $matches)) {
            return trim($matches[1]);
        }
    }
    
    return null;
}

/**
 * Verify user authentication
 * @return array|false
 */
function verifyAuth() {
    $token = getAuthToken();
    
    if (!$token) {
        return false;
    }
    
    return verifyToken($token);
}
?>
