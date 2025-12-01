# Release Notes - CartLink System v1.2.0

## Overview
This release addresses critical authorization issues and adds highly requested features including payment method selection, user profile management, and auto-fill capabilities.

## Summary of Changes

### 🔧 Bug Fixes

#### Fixed "Unauthorized access" Error
- **Problem**: Users were getting "Unauthorized access" error when trying to place orders
- **Root Cause**: The `getAuthToken()` function only used `getallheaders()` which is not available in all server environments
- **Solution**: 
  - Added multiple fallback methods for token retrieval
  - Updated `.htaccess` to properly pass Authorization headers
  - Now supports Apache, Nginx, and various server configurations

### ✨ New Features

#### 1. Payment Method Selection
**What it does**: Customers can now select from multiple payment methods during checkout

**Payment Methods Available**:
- 💵 Cash on Delivery (COD) - Default option
- 💳 Credit Card - Visa, Mastercard, American Express
- 💳 Debit Card - Direct payment from bank account
- 🅿️ PayPal - Secure PayPal payment
- 🏦 Bank Transfer - Direct bank transfer

**Implementation**:
- Added `payment_method` column to orders database table
- Updated checkout UI with payment method selection
- Payment method displayed in order history and details
- Admin panel shows payment method for all orders

#### 2. User Profile Management
**What it does**: Users can save and manage their personal information

**Features**:
- Update full name, phone number, and delivery address
- Change password with enhanced security requirements
- Profile information stored securely
- Easy access via "Profile" link in navigation

**Benefits**:
- Save time during checkout
- Reduce input errors
- Keep information up-to-date

#### 3. Auto-Fill During Checkout
**What it does**: Automatically fills delivery information from saved profile

**How it works**:
- When user navigates to checkout, system loads their profile
- Phone number and delivery address are pre-filled
- User can modify if needed for specific order
- Saves time and improves user experience

#### 4. Payment Method Confirmation
**What it does**: Confirms payment details before order placement

**How it works**:
- Shows confirmation dialog with:
  - Selected payment method
  - Total order amount
- User must confirm before order is placed
- Success message displays payment method used

## Technical Changes

### Database Changes
```sql
-- Added to orders table
payment_method ENUM('cod', 'credit_card', 'debit_card', 'paypal', 'bank_transfer') 
DEFAULT 'cod'

-- Added index for performance
INDEX idx_payment_method (payment_method)
```

### New API Endpoints
1. `GET /backend/api/auth/profile.php` - Get user profile
2. `POST /backend/api/auth/update_profile.php` - Update user profile
3. `POST /backend/api/auth/change_password.php` - Change password

### Updated API Endpoints
- `POST /backend/api/orders/create.php` - Now accepts `payment_method` parameter

### Security Improvements
1. **Enhanced Authorization**:
   - Multiple fallback methods for token retrieval
   - Compatible with various server configurations
   - Works in Apache, Nginx, and CLI environments

2. **Input Validation**:
   - All user inputs sanitized
   - Full name limited to 100 characters
   - XSS attack prevention

3. **Password Security**:
   - Stronger password requirements:
     - Minimum 8 characters (previously 6)
     - Must contain uppercase letter
     - Must contain lowercase letter
     - Must contain number
   - Password complexity validated on client and server
   - Passwords hashed using bcrypt

4. **Payment Method Validation**:
   - Server-side validation of payment method values
   - Only allowed values accepted
   - Defaults to safe value (COD)

## Files Changed

### Backend Files
- `backend/utils/helpers.php` - Enhanced token retrieval
- `backend/api/orders/create.php` - Added payment method support
- `backend/api/auth/profile.php` - New file
- `backend/api/auth/update_profile.php` - New file
- `backend/api/auth/change_password.php` - New file

### Frontend Files
- `frontend/customer/checkout.html` - Payment methods + auto-fill
- `frontend/customer/orders.html` - Payment method display
- `frontend/customer/cart.html` - Profile link in navigation
- `frontend/customer/profile.html` - New file
- `frontend/admin/orders.html` - Payment method display

### Database Files
- `database/schema.sql` - Updated with payment method
- `database/add_payment_methods.sql` - Migration for existing installations

### Configuration Files
- `.htaccess` - Updated to pass Authorization headers

### Documentation Files
- `README.md` - Updated with new features
- `PAYMENT_METHODS_UPDATE.md` - New file
- `USER_PROFILE_GUIDE.md` - New file
- `RELEASE_NOTES.md` - This file

## Installation Instructions

### For New Installations
Simply use the updated `database/schema.sql` file. All features are included.

### For Existing Installations
1. **Apply Database Migration**:
   ```bash
   mysql -u root -p cartlink_db < database/add_payment_methods.sql
   ```

2. **Update Code Files**:
   - Pull latest changes from repository
   - No configuration changes needed

3. **Test**:
   - Try logging in and placing an order
   - Test profile settings
   - Verify payment method selection

## Migration Guide

### From v1.0.0 to v1.2.0

1. **Backup Your Database**:
   ```bash
   mysqldump -u root -p cartlink_db > backup_before_upgrade.sql
   ```

2. **Apply Migration**:
   ```bash
   mysql -u root -p cartlink_db < database/add_payment_methods.sql
   ```

3. **Update Files**:
   - Replace all backend and frontend files
   - Keep your `backend/config/database.php` settings

4. **Clear Cache**:
   - Clear browser cache
   - Restart Apache if needed

5. **Test**:
   - Login with existing account
   - Place a test order
   - Update profile
   - Change password

## Breaking Changes
**None** - This release is fully backward compatible with v1.0.0

## Known Issues
None at this time.

## Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB
- Apache with mod_rewrite (or Nginx)
- PDO extension enabled

## Security Recommendations

### For Production Use
1. Enable HTTPS
2. Use environment variables for sensitive data
3. Implement rate limiting
4. Enable proper error logging
5. Regular security audits
6. Keep PHP and MySQL updated

### Password Policy
- Enforce 8+ character passwords
- Require uppercase, lowercase, and numbers
- Consider adding special character requirement
- Implement password expiry policy
- Prevent password reuse

## Support & Documentation

### Documentation
- [README.md](README.md) - General overview and setup
- [PAYMENT_METHODS_UPDATE.md](PAYMENT_METHODS_UPDATE.md) - Payment methods guide
- [USER_PROFILE_GUIDE.md](USER_PROFILE_GUIDE.md) - Profile settings guide

### Troubleshooting
Common issues and solutions are documented in:
- PAYMENT_METHODS_UPDATE.md (Troubleshooting section)
- USER_PROFILE_GUIDE.md (Troubleshooting section)

## Future Roadmap

### Planned Features (v1.3.0)
- Email notifications
- Product reviews and ratings
- Advanced search filters
- Customer wishlist
- Multiple delivery addresses per user

### Potential Future Enhancements
- Payment gateway integration (Stripe, Square)
- Mobile application
- Multi-language support
- Advanced analytics
- Loyalty program
- Gift cards

## Contributors
- Development Team
- Community Feedback

## License
This project is open-source and available for educational purposes.

---

**Version**: 1.2.0  
**Release Date**: December 2023  
**Status**: Production Ready  
**Compatibility**: Backward compatible with v1.0.0
