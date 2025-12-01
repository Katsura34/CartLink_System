# CartLink System - Payment Methods Update

## Overview
This update adds payment method selection to the order checkout process and fixes the "Unauthorized access" issue when placing orders.

## Changes Made

### 1. Fixed Authorization Issue
**Problem**: Users were getting "Unauthorized access" error when placing orders.

**Solution**: Updated the `getAuthToken()` function in `backend/utils/helpers.php` to support multiple server configurations:
- Added fallback methods for servers without `getallheaders()` function
- Added support for `$_SERVER['HTTP_AUTHORIZATION']`
- Added support for `$_SERVER['REDIRECT_HTTP_AUTHORIZATION']`
- Updated `.htaccess` to properly pass Authorization headers

### 2. Added Payment Method Feature
**Problem**: System lacked payment method selection during checkout.

**Solution**: Implemented comprehensive payment method support:

#### Supported Payment Methods:
1. **Cash on Delivery (COD)** - Default option
2. **Credit Card** - Visa, Mastercard, American Express
3. **Debit Card** - Direct payment from bank account
4. **PayPal** - Secure PayPal payment
5. **Bank Transfer** - Direct bank transfer

#### Database Changes:
- Added `payment_method` column to `orders` table
- Created migration file: `database/add_payment_methods.sql`
- Updated main schema file: `database/schema.sql`

#### Backend Changes:
- Updated `backend/api/orders/create.php` to accept and validate payment method
- Payment method defaults to 'cod' if not provided
- Added validation for payment method values

#### Frontend Changes:
- **Checkout Page** (`frontend/customer/checkout.html`):
  - Added payment method selection with radio buttons
  - Each option has descriptive text and icons
  - Payment method is sent to server when placing order

- **Customer Orders Page** (`frontend/customer/orders.html`):
  - Displays payment method in order list
  - Shows payment method in order details modal
  - Added helper functions for payment method display

- **Admin Orders Page** (`frontend/admin/orders.html`):
  - Added payment method column to orders table
  - Displays payment method in order details
  - Added helper functions for payment method display

## Installation Instructions

### For Existing Installations:

1. **Apply Database Migration**:
   ```sql
   -- Run this SQL script in phpMyAdmin or MySQL console
   USE cartlink_db;
   
   -- Add payment_method column
   ALTER TABLE orders 
   ADD COLUMN payment_method ENUM('cod', 'credit_card', 'debit_card', 'paypal', 'bank_transfer') 
   DEFAULT 'cod' 
   AFTER contact_phone;
   
   -- Update existing orders
   UPDATE orders SET payment_method = 'cod' WHERE payment_method IS NULL;
   
   -- Add index for better performance
   ALTER TABLE orders ADD INDEX idx_payment_method (payment_method);
   ```

   Or simply import the migration file:
   ```bash
   mysql -u root -p cartlink_db < database/add_payment_methods.sql
   ```

2. **Update Code Files**:
   - All code changes are in the repository
   - Pull the latest changes from the branch
   - No additional configuration needed

3. **Test the Changes**:
   - Try logging in and placing an order
   - Select different payment methods
   - Verify payment method is displayed in order list and details

### For New Installations:

1. **Use Updated Schema**:
   - The `database/schema.sql` file now includes payment method support
   - Simply import this file to create database with all features
   ```bash
   mysql -u root -p < database/schema.sql
   ```

2. **Configure and Run**:
   - Follow normal setup instructions in README.md
   - Payment methods will be available automatically

## Features

### Customer Features:
- Select payment method during checkout
- View payment method in order history
- See payment method in order details

### Admin Features:
- View payment method for all orders in order list
- See payment method in order details
- Filter and manage orders with payment information

## Security Improvements

1. **Enhanced Authorization Handling**:
   - Multiple fallback methods for token retrieval
   - Compatible with various server configurations
   - Works with Apache, Nginx, and CLI environments

2. **Payment Method Validation**:
   - Server-side validation of payment method values
   - Only allowed values are accepted
   - Defaults to safe value (COD) if not specified

## Usage

### Placing an Order (Customer):

1. Add items to cart
2. Go to checkout page
3. Fill in delivery information
4. Select preferred payment method:
   - Cash on Delivery (default)
   - Credit Card
   - Debit Card
   - PayPal
   - Bank Transfer
5. Add optional notes
6. Click "Place Order"

### Viewing Orders:

**Customer View**:
- Order list shows payment method for each order
- Order details modal displays payment method

**Admin View**:
- Orders table includes payment method column
- Order details show customer's selected payment method
- Can update order status as usual

## Troubleshooting

### "Unauthorized access" Error:
- **Fixed**: The updated code handles multiple authorization header formats
- If still occurring, check that Apache mod_rewrite is enabled
- Verify .htaccess file is being read (check AllowOverride in Apache config)

### Payment Method Not Showing:
- Ensure database migration has been applied
- Check browser console for JavaScript errors
- Clear browser cache and reload page

### Orders Not Creating:
- Check PHP error logs for details
- Verify database connection is working
- Ensure user is logged in (token is valid)

## API Changes

### Create Order Endpoint

**Endpoint**: `POST /backend/api/orders/create.php`

**New Request Format**:
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ],
  "delivery_address": "123 Main St",
  "contact_phone": "555-1234",
  "payment_method": "cod",  // NEW: optional, defaults to 'cod'
  "notes": "Please deliver in evening"
}
```

**Valid Payment Methods**:
- `cod` - Cash on Delivery
- `credit_card` - Credit Card
- `debit_card` - Debit Card
- `paypal` - PayPal
- `bank_transfer` - Bank Transfer

**Response**:
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "order_id": 123,
    "reference_number": "ORD-20231201-ABC123",
    "total_amount": 99.99,
    "payment_method": "cod"  // NEW: payment method confirmation
  }
}
```

## Future Enhancements

Potential future improvements:
1. Payment gateway integration for online payments
2. Payment status tracking
3. Partial payments support
4. Multiple payment methods per order
5. Payment receipts and invoices
6. Refund management

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review PHP error logs
3. Check browser console for frontend errors
4. Refer to main README.md for general setup

---

**Version**: 1.1.0  
**Date**: December 2023  
**Status**: Production Ready
