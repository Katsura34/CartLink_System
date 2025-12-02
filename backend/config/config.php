<?php
/**
 * Configuration Settings for CartLink System
 * Store sensitive configuration here
 * 
 * IMPORTANT: In production, use environment variables instead of hardcoding values
 */

// Application Settings
define('APP_NAME', 'CartLink System');
define('APP_VERSION', '1.0.0');

// Path Configuration (adjust based on deployment)
// For root deployment use: '/'
// For subdirectory use: '/CartLink_System/' or your folder name
define('APP_BASE_PATH', '/');

// Security Configuration
// ⚠️ CRITICAL: Generate a NEW secure random key for production!
// Use: openssl rand -base64 32
// Or: php -r "echo bin2hex(random_bytes(32));"
define('JWT_SECRET_KEY', 'CHANGE_THIS_TO_A_SECURE_RANDOM_KEY_IN_PRODUCTION_' . bin2hex(random_bytes(8)));
define('JWT_EXPIRY_HOURS', 24); // Token validity in hours

// API Configuration
define('API_BASE_URL', APP_BASE_PATH . 'backend/api');

// Frontend paths
define('CUSTOMER_BASE_URL', APP_BASE_PATH . 'frontend/customer/');
define('ADMIN_BASE_URL', APP_BASE_PATH . 'frontend/admin/');

// Database Configuration
// IMPORTANT: Change these for production!
define('DB_HOST', 'localhost');
define('DB_NAME', 'cartlink_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Error Reporting (disable in production)
define('DISPLAY_ERRORS', true); // Set to false in production

// CORS Settings
define('CORS_ALLOWED_ORIGINS', '*'); // Restrict to specific domains in production
define('CORS_ALLOWED_METHODS', 'GET, POST, PUT, DELETE, OPTIONS');
define('CORS_ALLOWED_HEADERS', 'Content-Type, Authorization');

// Security Settings
define('PASSWORD_MIN_LENGTH', 8); // Minimum 8 characters for adequate security
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Order Settings
define('ORDER_REFERENCE_PREFIX', 'ORD');
define('LOW_STOCK_THRESHOLD', 10);

// Pagination Settings
define('DEFAULT_PAGE_SIZE', 30);
define('MAX_PAGE_SIZE', 100);

// File Upload Settings (for future use)
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Email Settings (for future use)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('FROM_EMAIL', 'noreply@cartlink.com');
define('FROM_NAME', 'CartLink System');

// Return configuration as array for easy access
function getConfig() {
    return [
        'app' => [
            'name' => APP_NAME,
            'version' => APP_VERSION,
            'base_path' => APP_BASE_PATH,
        ],
        'security' => [
            'jwt_secret' => JWT_SECRET_KEY,
            'jwt_expiry' => JWT_EXPIRY_HOURS,
            'password_min_length' => PASSWORD_MIN_LENGTH,
        ],
        'database' => [
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASS,
            'charset' => DB_CHARSET,
        ],
        'paths' => [
            'customer' => CUSTOMER_BASE_URL,
            'admin' => ADMIN_BASE_URL,
            'api' => API_BASE_URL,
        ]
    ];
}
?>
