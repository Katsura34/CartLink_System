# Order Backend Authorization Fix - Testing Guide

## Problem Addressed
Users were experiencing "Unauthorized access" errors when:
- Placing orders via `/backend/api/orders/create.php`
- Viewing orders via `/backend/api/orders/list.php` and `/backend/api/orders/get.php`
- Updating profile via `/backend/api/auth/update_profile.php`

## Root Cause
The Authorization header containing the Bearer token was not being properly extracted in various server configurations (Apache, nginx, CGI, FastCGI, etc.) due to differences in how HTTP headers are passed to PHP.

## Solution Implemented

### 1. Enhanced Authorization Header Extraction
The `getAuthToken()` function in `backend/utils/helpers.php` now checks 5 different sources for the Authorization header:

1. **getallheaders()** - Works with Apache mod_php
2. **$_SERVER['HTTP_AUTHORIZATION']** - Standard CGI/FastCGI
3. **$_SERVER['REDIRECT_HTTP_AUTHORIZATION']** - Apache with mod_rewrite
4. **$_SERVER['PHP_AUTH_BEARER']** - Direct bearer token
5. **$_ENV['HTTP_AUTHORIZATION']** - Environment variable (set by .htaccess)

### 2. Improved .htaccess Configuration
Added multiple methods to pass Authorization headers:
- `RewriteRule` with environment variable
- `SetEnvIf` directive for CGI/FastCGI
- Support for both uppercase and lowercase header names
- Explicit OPTIONS request handling

### 3. Consistent CORS Handling
Added OPTIONS request handling to all order endpoints to properly handle preflight requests.

## Testing the Fix

### Automated Tests
Run the included test scripts to verify the fix:

```bash
# Test token generation and verification
php /tmp/test_token.php

# Test authorization header extraction
php /tmp/test_auth_header.php

# Test complete order flow
php /tmp/test_order_flow.php
```

All tests should pass with ✓ marks.

### Manual Testing via Browser

#### 1. Test with Debug Endpoint
First, ensure you're logged in and have a valid token in localStorage.

```javascript
// In browser console:
fetch('/CartLink_System/backend/api/auth/test_auth.php', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json'
    }
})
.then(r => r.json())
.then(data => console.log(data));
```

Expected result: `success: true` with authentication data.

#### 2. Test Order Creation
```javascript
// In browser console, after login:
const orderData = {
    items: [
        { product_id: 1, quantity: 2 }
    ],
    delivery_address: "123 Test St, Test City",
    contact_phone: "1234567890",
    payment_method: "cod",
    notes: "Test order"
};

fetch('/CartLink_System/backend/api/orders/create.php', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(orderData)
})
.then(r => r.json())
.then(data => console.log(data));
```

Expected result: `success: true` with order details.

#### 3. Test Profile Update
```javascript
// In browser console, after login:
const profileData = {
    full_name: "Updated Name",
    phone: "1234567890",
    address: "456 New St, New City"
};

fetch('/CartLink_System/backend/api/auth/update_profile.php', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(profileData)
})
.then(r => r.json())
.then(data => console.log(data));
```

Expected result: `success: true` with updated profile data.

## Verification Checklist

- [x] Token generation works correctly
- [x] Token verification works correctly
- [x] Authorization header extraction works from all 5 sources
- [x] Expired tokens are properly rejected
- [x] Invalid tokens are properly rejected
- [x] Missing tokens are properly rejected
- [x] OPTIONS requests are handled correctly
- [x] No syntax errors in modified files
- [x] Code review feedback addressed
- [x] Security issues fixed (no token exposure in debug output)

## Troubleshooting

If you still experience "Unauthorized access" errors:

1. **Check token is stored**: Open browser console and run `localStorage.getItem('token')`
2. **Check token is valid**: Use the debug endpoint `/backend/api/auth/test_auth.php`
3. **Check server configuration**: Verify `.htaccess` is being read (check with `phpinfo()`)
4. **Check PHP version**: Ensure PHP 7.4+ is installed
5. **Check Apache modules**: Ensure mod_rewrite and mod_headers are enabled

## Files Modified

- `backend/utils/helpers.php` - Enhanced `getAuthToken()` function
- `backend/api/orders/list.php` - Added OPTIONS handling
- `backend/api/orders/get.php` - Added OPTIONS handling
- `.htaccess` - Enhanced Authorization header passing
- `backend/api/auth/test_auth.php` - New debug endpoint (NEW)

## Backward Compatibility

All changes are fully backward compatible. The enhanced `getAuthToken()` function includes all previous methods plus additional fallbacks, so existing working configurations will continue to work.

## Security Notes

- Tokens are not exposed in debug output (only length and format)
- Token validation includes expiration checking
- Token signature verification prevents tampering
- Authorization header is checked via multiple secure methods
