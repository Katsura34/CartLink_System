# CartLink System - Quick Setup Guide

## 🚀 Getting Started in 5 Minutes

### Step 1: Install XAMPP (if not already installed)
Download from: https://www.apachefriends.org/

### Step 2: Copy Project to XAMPP
1. Locate your XAMPP `htdocs` folder:
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`
2. Copy the entire `CartLink_System` folder into `htdocs`

### Step 3: Start XAMPP Services
1. Open XAMPP Control Panel
2. Click "Start" for **Apache**
3. Click "Start" for **MySQL**

### Step 4: Create Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "Import" tab
3. Click "Choose File" and select: `CartLink_System/database/schema.sql`
4. Click "Go" button
5. Wait for success message

### Step 5: Access the System

**Customer Interface:**
- Open browser: `http://localhost/CartLink_System/frontend/customer/index.html`

**Admin Interface:**
- Open browser: `http://localhost/CartLink_System/frontend/admin/dashboard.html`
- Login with:
  - Email: `admin@cartlink.com`
  - Password: `admin123`

## 📝 Test the System

### As Customer:
1. Register a new account
2. Browse products
3. Add items to cart
4. Checkout and place order
5. View order status

### As Admin:
1. Login to admin dashboard
2. View dashboard statistics
3. Manage products (add/edit/delete)
4. View and update order statuses
5. Monitor low-stock items

## ⚠️ Troubleshooting

### Database Connection Error
- Check MySQL is running in XAMPP
- Verify database name is `cartlink_db`
- Check credentials in `backend/config/database.php`

### Page Not Found (404)
- Ensure project is in correct `htdocs` folder
- Verify Apache is running
- Use `http://localhost/` not `file://`

### API Not Working
- Check browser console for errors
- Verify API path in requests
- Ensure CORS is enabled

## 🎯 Quick Links

- Customer Home: http://localhost/CartLink_System/frontend/customer/index.html
- Admin Dashboard: http://localhost/CartLink_System/frontend/admin/dashboard.html
- phpMyAdmin: http://localhost/phpmyadmin

## 📊 System Features

✅ User Authentication (Login/Register)
✅ Product Catalog with Search
✅ Shopping Cart
✅ Checkout & Order Placement
✅ Order Tracking
✅ Admin Dashboard
✅ Product Management
✅ Order Management
✅ Inventory Control
✅ Real-time Stock Updates

## 🔐 Default Credentials

**Admin Account:**
- Email: admin@cartlink.com
- Password: admin123

**Sample Products:**
- 8 products across 5 categories
- Pre-loaded with stock quantities

## 💡 Tips

- Always start Apache and MySQL before accessing the system
- Keep phpMyAdmin open to monitor database changes
- Use browser developer tools (F12) to debug issues
- Clear browser cache if styles don't load properly

## 📚 Next Steps

1. Customize the design in `frontend/assets/css/style.css`
2. Add product images (use image URLs)
3. Modify categories in database
4. Test complete order workflow
5. Create customer accounts and place test orders

## 🆘 Need Help?

Check the main README.md for:
- Complete API documentation
- Project structure details
- Security features
- Advanced configuration

---

**Happy coding! 🚀**
