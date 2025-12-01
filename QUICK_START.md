# Quick Start Guide - CartLink System v1.2.0

## 🚀 Get Started in 3 Minutes!

This guide will help you quickly test all the new features.

---

## Step 1: Apply Database Migration (Existing Installations Only)

If you have an existing CartLink installation, run this command:

```bash
# Navigate to your XAMPP/MySQL bin directory
cd C:\xampp\mysql\bin

# Run the migration
mysql -u root -p cartlink_db < "C:\xampp\htdocs\CartLink_System\database\add_payment_methods.sql"
```

**For new installations**: Just import `database/schema.sql` as usual - everything is already included!

---

## Step 2: Test the Fixes

### ✅ Test 1: "Unauthorized Access" Fix

**Before**: Getting error when placing orders  
**After**: Orders work smoothly!

**How to test**:
1. Login at: `http://localhost/CartLink_System/frontend/customer/login.html`
2. Add some products to cart
3. Go to checkout
4. Fill in the form and place order
5. ✅ **Should work without "Unauthorized access" error!**

---

## Step 3: Test New Features

### ✨ Feature 1: User Profile Settings

**What**: Save your address and phone so you don't have to type them every time

**How to test**:
1. Login to your account
2. Click **"Profile"** in the navigation menu
3. Fill in your information:
   - Full Name: John Doe
   - Phone: 555-1234
   - Address: 123 Main Street, City, State 12345
4. Click **"Save Changes"**
5. ✅ **You should see "Profile updated successfully!"**

---

### ✨ Feature 2: Auto-Fill at Checkout

**What**: Your saved address and phone automatically appear at checkout

**How to test**:
1. Add products to cart
2. Go to checkout page
3. ✅ **Notice the address and phone fields are already filled!**
4. You can still change them if needed for this specific order

---

### ✨ Feature 3: Payment Method Selection

**What**: Choose how you want to pay (5 options available)

**How to test**:
1. Go to checkout page
2. Scroll down to see payment method options:
   - 💵 Cash on Delivery (default)
   - 💳 Credit Card
   - 💳 Debit Card
   - 🅿️ PayPal
   - 🏦 Bank Transfer
3. Select one (try "Credit Card")
4. Continue with order
5. ✅ **You should see the payment method you selected**

---

### ✨ Feature 4: Payment Confirmation

**What**: System asks to confirm payment method before placing order

**How to test**:
1. Fill out checkout form
2. Select a payment method
3. Click **"Place Order"**
4. ✅ **A confirmation dialog should appear showing:**
   - Payment Method: [Your selection]
   - Total Amount: $X.XX
5. Click **OK** to confirm
6. ✅ **Order placed with payment method shown in success message!**

---

### ✨ Feature 5: View Payment Methods in Orders

**What**: See which payment method was used for each order

**Customer View**:
1. Go to "My Orders" page
2. ✅ **Each order shows the payment method used**
3. Click "View Details" on any order
4. ✅ **Payment method shown in order details**

**Admin View**:
1. Login as admin (admin@cartlink.com / admin123)
2. Go to Orders page
3. ✅ **Payment column shows payment method for all orders**
4. Click "View" on any order
5. ✅ **Payment method shown in customer information**

---

### ✨ Feature 6: Change Password

**What**: Update your password with enhanced security

**How to test**:
1. Go to Profile page
2. Scroll to "Change Password" section
3. Enter:
   - Current Password: [your current password]
   - New Password: NewPass123 (must have uppercase, lowercase, and number)
   - Confirm: NewPass123
4. Click **"Change Password"**
5. ✅ **You should see "Password changed successfully!"**

**Note**: Password must be:
- At least 8 characters
- Contains uppercase letter (A-Z)
- Contains lowercase letter (a-z)
- Contains number (0-9)

---

## 🎯 Quick Test Scenario

### Complete User Journey (5 minutes)

1. **Register/Login**
   ```
   Go to: http://localhost/CartLink_System/frontend/customer/login.html
   ```

2. **Set Up Profile**
   - Click "Profile" in menu
   - Add your default address and phone
   - Save changes

3. **Browse & Shop**
   - Go to Products page
   - Add 2-3 items to cart

4. **Checkout**
   - Go to cart, click "Proceed to Checkout"
   - Verify address and phone are auto-filled ✨
   - Select "Credit Card" as payment method ✨
   - Click "Place Order"
   - Confirm payment method in dialog ✨
   - ✅ Order placed successfully!

5. **View Order**
   - Go to "My Orders"
   - See your order with payment method displayed ✨
   - Click "View Details"
   - See complete order information with payment method ✨

---

## 🔍 Troubleshooting Quick Fixes

### Problem: Profile page not loading
**Fix**: Make sure you're logged in. Try logging out and back in.

### Problem: Auto-fill not working
**Fix**: 
1. Go to Profile page
2. Make sure phone and address are saved
3. Click "Save Changes"
4. Refresh checkout page

### Problem: Payment method not showing in orders
**Fix**: 
1. Make sure database migration was applied
2. Check browser console for errors
3. Try placing a new order

### Problem: Can't change password
**Fix**: Make sure new password has:
- At least 8 characters
- One uppercase letter
- One lowercase letter
- One number

---

## 📱 Navigation Quick Reference

### Customer Pages
- **Home**: /frontend/customer/index.html
- **Products**: /frontend/customer/products.html
- **Cart**: /frontend/customer/cart.html
- **Checkout**: /frontend/customer/checkout.html
- **🆕 Profile**: /frontend/customer/profile.html
- **Orders**: /frontend/customer/orders.html
- **Login**: /frontend/customer/login.html

### Admin Pages
- **Dashboard**: /frontend/admin/dashboard.html
- **Products**: /frontend/admin/products.html
- **Orders**: /frontend/admin/orders.html (now shows payment methods!)
- **Categories**: /frontend/admin/categories.html

---

## 🎉 Success Indicators

You know everything is working when:

✅ No "Unauthorized access" errors when placing orders  
✅ Profile page loads and saves your information  
✅ Checkout auto-fills your address and phone  
✅ Payment method options appear at checkout  
✅ Confirmation dialog shows before order placement  
✅ Payment method displays in order history  
✅ Password change requires stronger passwords  

---

## 📚 More Information

For detailed documentation:
- **Payment Methods**: See `PAYMENT_METHODS_UPDATE.md`
- **Profile Settings**: See `USER_PROFILE_GUIDE.md`
- **All Changes**: See `RELEASE_NOTES.md`
- **Complete Summary**: See `IMPLEMENTATION_COMPLETE.md`

---

## 🆘 Need Help?

1. Check the documentation files listed above
2. Look at troubleshooting sections
3. Check browser console (F12) for errors
4. Review the error messages

---

## 🎊 Enjoy Your Enhanced CartLink System!

All features are ready to use. Happy shopping! 🛒

---

**Quick Start Version**: 1.0  
**System Version**: 1.2.0  
**Status**: Ready to Use ✅
