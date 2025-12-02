# Local Testing Guide

This guide helps you configure CartLink System for different deployment scenarios.

## Configuration for Local Testing (PHP Built-in Server)

When testing locally using PHP's built-in server (e.g., `php -S localhost:8000`):

### 1. Frontend Configuration
Edit `frontend/assets/js/main.js`:
```javascript
const CONFIG = {
    basePath: '', // Empty string for root deployment
    apiPath: '/backend/api'
};
```

### 2. Backend Configuration
Edit `backend/config/config.php`:
```php
define('APP_BASE_PATH', '/');
```

### 3. Start PHP Server
```bash
cd /path/to/CartLink_System
php -S localhost:8000
```

### 4. Access Application
- Homepage: `http://localhost:8000/frontend/customer/index.html`
- Admin: `http://localhost:8000/frontend/admin/dashboard.html`

---

## Configuration for XAMPP/Apache (Subdirectory)

When deploying in XAMPP's htdocs folder:

### 1. Frontend Configuration
Edit `frontend/assets/js/main.js`:
```javascript
const CONFIG = {
    basePath: '/CartLink_System', // Your htdocs folder name
    apiPath: '/backend/api'
};
```

### 2. Backend Configuration
Edit `backend/config/config.php`:
```php
define('APP_BASE_PATH', '/CartLink_System/');
```

### 3. Place in htdocs
```
C:\xampp\htdocs\CartLink_System\
```

### 4. Access Application
- Homepage: `http://localhost/CartLink_System/frontend/customer/index.html`
- Admin: `http://localhost/CartLink_System/frontend/admin/dashboard.html`

---

## Configuration for Root Deployment

When deploying at the domain root:

### 1. Frontend Configuration
Edit `frontend/assets/js/main.js`:
```javascript
const CONFIG = {
    basePath: '', // Empty string for root
    apiPath: '/backend/api'
};
```

### 2. Backend Configuration
Edit `backend/config/config.php`:
```php
define('APP_BASE_PATH', '/');
```

### 3. Access Application
- Homepage: `http://yourdomain.com/frontend/customer/index.html`
- Admin: `http://yourdomain.com/frontend/admin/dashboard.html`

---

## Default Credentials

**Admin Account:**
- Email: `admin@cartlink.com`
- Password: `admin123`

---

## Database Setup

### For Local Testing:

1. **Start MySQL:**
   ```bash
   sudo service mysql start
   ```

2. **Create Database:**
   ```bash
   mysql -u root -e "CREATE DATABASE cartlink_db;"
   ```

3. **Import Schema:**
   ```bash
   mysql -u root cartlink_db < database/schema.sql
   ```

4. **Configure Connection:**
   Edit `backend/config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'cartlink_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

---

## Quick Switch Script

Save this as `switch-config.sh` for easy switching:

```bash
#!/bin/bash

if [ "$1" == "local" ]; then
    echo "Switching to local testing configuration..."
    sed -i "s|basePath: '.*'|basePath: ''|g" frontend/assets/js/main.js
    sed -i "s|define('APP_BASE_PATH', '.*');|define('APP_BASE_PATH', '/');|g" backend/config/config.php
    echo "✓ Configuration updated for local testing"
elif [ "$1" == "xampp" ]; then
    echo "Switching to XAMPP configuration..."
    sed -i "s|basePath: '.*'|basePath: '/CartLink_System'|g" frontend/assets/js/main.js
    sed -i "s|define('APP_BASE_PATH', '.*');|define('APP_BASE_PATH', '/CartLink_System/');|g" backend/config/config.php
    echo "✓ Configuration updated for XAMPP"
else
    echo "Usage: ./switch-config.sh [local|xampp]"
fi
```

Make it executable:
```bash
chmod +x switch-config.sh
```

Use it:
```bash
./switch-config.sh local   # For local testing
./switch-config.sh xampp   # For XAMPP
```

---

## Troubleshooting

### Issue: 404 errors on API calls
**Solution:** Check that `basePath` matches your deployment structure.

### Issue: CORS errors
**Solution:** Always access via `http://localhost`, never `file://`

### Issue: Database connection failed
**Solution:** Verify MySQL is running and credentials in `config.php` are correct.

### Issue: "Unauthorized access" errors
**Solution:** Clear browser localStorage and login again:
```javascript
localStorage.clear();
location.reload();
```

---

## Testing Checklist

- [ ] Homepage loads with featured products
- [ ] Products page displays all items
- [ ] Add to cart functionality works
- [ ] Cart page shows items correctly
- [ ] Login/Register pages work
- [ ] Admin dashboard loads statistics
- [ ] Admin can manage products
- [ ] Admin can view orders
- [ ] Admin can view categories

---

**Current Configuration:** Local Testing (PHP Built-in Server)
**Last Updated:** December 2025
