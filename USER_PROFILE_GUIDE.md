# User Profile Settings Guide

## Overview
The CartLink System now includes a comprehensive user profile management feature that allows customers to save their personal information, including default delivery address and phone number.

## Features

### 1. Profile Management
Users can now manage their profile information including:
- Full name
- Phone number (saved for auto-fill during checkout)
- Default delivery address (saved for auto-fill during checkout)

### 2. Password Management
Users can change their password with enhanced security requirements:
- Minimum 8 characters
- Must contain at least one uppercase letter
- Must contain at least one lowercase letter
- Must contain at least one number

### 3. Auto-Fill During Checkout
When a user has saved their phone number and address in their profile:
- These fields are automatically filled in the checkout page
- Users can still modify the information if needed
- Saves time and reduces errors during order placement

## How to Use

### Accessing Profile Settings

1. **Login to your account**
   - Navigate to the login page
   - Enter your credentials

2. **Access Profile**
   - Click on "Profile" in the navigation menu
   - Or navigate to `/frontend/customer/profile.html`

### Updating Profile Information

1. **Edit Your Information**
   - Update your full name (required)
   - Add or update your phone number
   - Add or update your default delivery address

2. **Save Changes**
   - Click "Save Changes" button
   - You'll see a success message confirming the update

3. **Benefits**
   - Your information is stored securely
   - Auto-fills during checkout
   - Can be updated anytime

### Changing Password

1. **Access Password Change Form**
   - Scroll down to "Change Password" section on profile page

2. **Enter Required Information**
   - Current password
   - New password (must meet security requirements)
   - Confirm new password

3. **Password Requirements**
   - Minimum 8 characters
   - At least one uppercase letter (A-Z)
   - At least one lowercase letter (a-z)
   - At least one number (0-9)

4. **Submit**
   - Click "Change Password" button
   - You'll see a success message
   - Use your new password for future logins

### Using Auto-Fill During Checkout

1. **Add Items to Cart**
   - Browse products and add items

2. **Proceed to Checkout**
   - Go to cart and click "Proceed to Checkout"

3. **Auto-Filled Information**
   - Phone number field will be pre-filled with your saved number
   - Delivery address field will be pre-filled with your saved address
   - You can modify these fields if needed for a specific order

4. **Complete Order**
   - Select payment method
   - Add any optional notes
   - Confirm and place order

## API Endpoints

### Get User Profile
```
GET /backend/api/auth/profile.php
```

**Headers:**
- Authorization: Bearer {token}

**Response:**
```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "phone": "555-1234",
    "address": "123 Main St, City, State 12345",
    "role": "customer",
    "created_at": "2023-01-15 10:30:00"
  }
}
```

### Update Profile
```
POST /backend/api/auth/update_profile.php
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: application/json

**Request Body:**
```json
{
  "full_name": "John Doe",
  "phone": "555-1234",
  "address": "123 Main St, City, State 12345"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "phone": "555-1234",
    "address": "123 Main St, City, State 12345",
    "role": "customer"
  }
}
```

### Change Password
```
POST /backend/api/auth/change_password.php
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: application/json

**Request Body:**
```json
{
  "current_password": "oldPassword123",
  "new_password": "NewPass123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

**Error Response (Wrong Current Password):**
```json
{
  "success": false,
  "message": "Current password is incorrect"
}
```

**Error Response (Weak Password):**
```json
{
  "success": false,
  "message": "Password must contain at least one uppercase letter, one lowercase letter, and one number"
}
```

## Security Features

### 1. Input Validation
- All user inputs are validated on both client and server side
- Full name limited to 100 characters
- All inputs are sanitized to prevent XSS attacks

### 2. Password Security
- Strong password requirements enforced
- Passwords are hashed using bcrypt
- Current password verification required before change
- No password is ever stored in plain text

### 3. Authentication
- All profile operations require valid JWT token
- Users can only access and modify their own profile
- Token expiration is enforced (24 hours)

### 4. Authorization
- Profile endpoints verify user authentication
- Users cannot access other users' profiles
- Admin privileges required for admin operations

## Best Practices

### For Users

1. **Keep Profile Updated**
   - Update your information when you move or change phone number
   - Ensures smooth order delivery

2. **Use Strong Password**
   - Follow password requirements
   - Change password periodically
   - Don't share your password

3. **Verify Auto-Fill**
   - Check auto-filled address before placing order
   - Update if you need delivery to a different location

### For Developers

1. **Data Validation**
   - Always validate user input
   - Use prepared statements for database queries
   - Sanitize all inputs

2. **Error Handling**
   - Provide clear error messages
   - Log errors for debugging
   - Don't expose sensitive information in errors

3. **Security**
   - Keep authentication tokens secure
   - Implement rate limiting
   - Regular security audits

## Troubleshooting

### Profile Not Loading
**Problem:** Profile page shows error or doesn't load data

**Solutions:**
1. Ensure you're logged in
2. Check browser console for errors
3. Verify token is valid (not expired)
4. Try logging out and back in

### Auto-Fill Not Working
**Problem:** Checkout doesn't auto-fill address/phone

**Solutions:**
1. Ensure profile has phone and address saved
2. Check that you're logged in
3. Try refreshing the checkout page
4. Verify profile data by visiting profile page

### Password Change Fails
**Problem:** Cannot change password

**Solutions:**
1. Verify current password is correct
2. Ensure new password meets requirements:
   - At least 8 characters
   - Uppercase letter
   - Lowercase letter
   - Number
3. Make sure new password and confirmation match

### Profile Update Fails
**Problem:** Cannot save profile changes

**Solutions:**
1. Check that full name is provided
2. Ensure you're logged in
3. Check browser console for errors
4. Verify network connection

## Future Enhancements

Potential improvements for future versions:
1. Profile picture upload
2. Multiple delivery addresses
3. Email verification for changes
4. Two-factor authentication
5. Order preferences
6. Notification settings
7. Wishlist management
8. Address book with multiple addresses

## Support

For issues or questions:
1. Check this guide
2. Review troubleshooting section
3. Check browser console for errors
4. Contact system administrator

---

**Version:** 1.2.0  
**Last Updated:** December 2023  
**Status:** Production Ready
