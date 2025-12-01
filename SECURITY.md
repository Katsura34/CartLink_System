# Security Configuration Guide

## ⚠️ IMPORTANT: Production Deployment

This system includes default credentials and configuration values that are **ONLY SUITABLE FOR DEVELOPMENT**. Before deploying to production, you MUST make the following changes:

## 🔐 Critical Security Updates

### 1. Change Admin Password

The default admin password is `admin123`. After first deployment:

1. Login as admin
2. Manually update the password in the database using phpMyAdmin:
   ```sql
   UPDATE users 
   SET password = '$2y$10$YOUR_NEW_HASH_HERE' 
   WHERE email = 'admin@cartlink.com';
   ```
3. Generate a new hash using PHP:
   ```php
   echo password_hash('your_new_secure_password', PASSWORD_BCRYPT);
   ```

### 2. Update JWT Secret Key

**File:** `backend/config/config.php`

Change the JWT secret key to a random, secure value:

```php
// BEFORE (Development - DO NOT USE IN PRODUCTION)
define('JWT_SECRET_KEY', 'cartlink_secret_key_2024_CHANGE_IN_PRODUCTION');

// AFTER (Production - use a secure random key)
define('JWT_SECRET_KEY', 'your_secure_random_key_here');
```

Generate a secure key using:
```bash
openssl rand -base64 32
```

Or in PHP:
```php
echo bin2hex(random_bytes(32));
```

### 3. Update Database Credentials

**File:** `backend/config/config.php`

Change the default database credentials:

```php
// BEFORE (Development - XAMPP defaults)
define('DB_HOST', 'localhost');
define('DB_NAME', 'cartlink_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// AFTER (Production - your secure credentials)
define('DB_HOST', 'your_db_host');
define('DB_NAME', 'your_db_name');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_secure_password');
```

### 4. Disable Error Display

**File:** `backend/config/config.php`

```php
// BEFORE (Development)
define('DISPLAY_ERRORS', true);

// AFTER (Production)
define('DISPLAY_ERRORS', false);
```

### 5. Restrict CORS Origins

**File:** `backend/config/config.php`

```php
// BEFORE (Development - allows all origins)
define('CORS_ALLOWED_ORIGINS', '*');

// AFTER (Production - specific domain only)
define('CORS_ALLOWED_ORIGINS', 'https://yourdomain.com');
```

### 6. Update Base Path Configuration

**File:** `backend/config/config.php`

If deploying to root directory:
```php
define('APP_BASE_PATH', '/');
```

If deploying to subdirectory:
```php
define('APP_BASE_PATH', '/your-folder-name/');
```

**File:** `frontend/assets/js/main.js`

Update the CONFIG object:
```javascript
const CONFIG = {
    basePath: '/your-folder-name', // or '/' for root
    apiPath: '/backend/api'
};
```

## 🔒 Additional Security Recommendations

### Use Environment Variables

Instead of hardcoding sensitive values, use environment variables:

```php
// Example using getenv()
define('DB_PASS', getenv('DB_PASSWORD'));
define('JWT_SECRET_KEY', getenv('JWT_SECRET'));
```

### Enable HTTPS

Always use HTTPS in production:
- Obtain SSL certificate (Let's Encrypt is free)
- Configure Apache to redirect HTTP to HTTPS
- Update CORS to only allow HTTPS origins

### Regular Security Updates

- Keep PHP updated to latest stable version
- Update MySQL/MariaDB regularly
- Monitor security advisories
- Review logs regularly

### Database Security

- Create a dedicated database user with limited privileges
- Do NOT use root user in production
- Enable MySQL strict mode
- Regular database backups

### File Permissions

Set proper file permissions:
```bash
# Files: read-only for web server
chmod 644 *.php *.html *.css *.js

# Directories: executable for web server
chmod 755 backend/ frontend/ database/

# Config files: read-only, owner only
chmod 600 backend/config/config.php
```

### Web Server Configuration

Add to `.htaccess`:
```apache
# Prevent access to sensitive files
<FilesMatch "^(config\.php|\.env|\.git)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Security headers
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
```

## 📋 Security Checklist

Before going live, verify:

- [ ] Changed default admin password
- [ ] Generated new JWT secret key
- [ ] Updated database credentials
- [ ] Disabled error display
- [ ] Restricted CORS origins
- [ ] Enabled HTTPS
- [ ] Set proper file permissions
- [ ] Created dedicated database user
- [ ] Removed development/test accounts
- [ ] Reviewed and tested all endpoints
- [ ] Configured backup system
- [ ] Set up monitoring and logging
- [ ] Tested rate limiting (if implemented)
- [ ] Reviewed security headers

## 🆘 Security Incident Response

If you discover a security issue:

1. Immediately disable affected functionality
2. Review server logs for unauthorized access
3. Change all passwords and keys
4. Notify users if data was compromised
5. Apply security patches
6. Document the incident

## 📚 Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [MySQL Security](https://dev.mysql.com/doc/refman/8.0/en/security.html)

---

**Remember: Security is an ongoing process, not a one-time setup!**
