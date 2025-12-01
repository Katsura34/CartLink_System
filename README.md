# CartLink System - Web-Based Ordering System

A complete web-based ordering system built with PHP API backend, MySQL database, and HTML/CSS/JavaScript frontend.

## Features

### Customer Features
- User registration and login
- Browse products with search and category filtering
- Shopping cart functionality
- Secure checkout process
- Order tracking and history
- Real-time order status updates

### Admin Features
- Admin dashboard with analytics
- Product management (CRUD operations)
- Order management with status updates
- Category management
- Inventory tracking and low-stock alerts
- Customer order viewing

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+ / MariaDB
- **Server**: Apache (XAMPP recommended for local development)

## Installation Instructions

### Prerequisites
- XAMPP (or similar PHP/MySQL stack)
- Web browser (Chrome, Firefox, Safari, Edge)
- Text editor (VS Code, Sublime Text, etc.)

### Step 1: Setup XAMPP
1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services from XAMPP Control Panel

### Step 2: Clone/Copy Project
1. Copy the entire `CartLink_System` folder to your `htdocs` directory
   - Default location: `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (Mac)
2. The final path should be: `htdocs/CartLink_System/`

### Step 3: Setup Database
1. Open phpMyAdmin in your browser: `http://localhost/phpmyadmin`
2. Create a new database named `cartlink_db` or use the SQL file:
   - Click on "Import" tab
   - Select `database/schema.sql` file from the project
   - Click "Go" to execute
3. The database will be created with:
   - All required tables
   - Sample categories and products
   - Default admin user (see credentials below)

### Step 4: Configure Database Connection
1. Open `backend/config/database.php`
2. Update credentials if needed (default XAMPP settings):
   ```php
   private $host = "localhost";
   private $db_name = "cartlink_db";
   private $username = "root";
   private $password = "";
   ```

### Step 5: Access the Application

#### Customer Interface
- Homepage: `http://localhost/CartLink_System/frontend/customer/index.html`
- Products: `http://localhost/CartLink_System/frontend/customer/products.html`
- Login: `http://localhost/CartLink_System/frontend/customer/login.html`
- Register: `http://localhost/CartLink_System/frontend/customer/register.html`

#### Admin Interface
- Dashboard: `http://localhost/CartLink_System/frontend/admin/dashboard.html`
- Products: `http://localhost/CartLink_System/frontend/admin/products.html`
- Orders: `http://localhost/CartLink_System/frontend/admin/orders.html`

## Default Credentials

### Admin Account
- **Email**: admin@cartlink.com
- **Password**: admin123

## Project Structure

```
CartLink_System/
├── backend/
│   ├── api/
│   │   ├── auth/          # Authentication endpoints
│   │   ├── products/      # Product management endpoints
│   │   ├── categories/    # Category endpoints
│   │   └── orders/        # Order management endpoints
│   ├── config/
│   │   └── database.php   # Database configuration
│   └── utils/
│       └── helpers.php    # Utility functions
├── frontend/
│   ├── customer/          # Customer-facing pages
│   │   ├── index.html
│   │   ├── products.html
│   │   ├── cart.html
│   │   ├── checkout.html
│   │   ├── orders.html
│   │   ├── login.html
│   │   └── register.html
│   ├── admin/             # Admin pages
│   │   ├── dashboard.html
│   │   ├── products.html
│   │   ├── orders.html
│   │   └── categories.html
│   └── assets/
│       ├── css/
│       │   └── style.css
│       └── js/
│           └── main.js
├── database/
│   └── schema.sql         # Database schema and seed data
└── README.md
```

## API Endpoints

### Authentication
- `POST /backend/api/auth/login.php` - User login
- `POST /backend/api/auth/register.php` - User registration

### Products
- `GET /backend/api/products/list.php` - List all products
- `GET /backend/api/products/get.php?id={id}` - Get single product
- `POST /backend/api/products/create.php` - Create product (Admin)
- `PUT /backend/api/products/update.php` - Update product (Admin)
- `DELETE /backend/api/products/delete.php` - Delete product (Admin)

### Categories
- `GET /backend/api/categories/list.php` - List all categories

### Orders
- `POST /backend/api/orders/create.php` - Create order
- `GET /backend/api/orders/list.php` - List orders
- `GET /backend/api/orders/get.php?id={id}` - Get order details
- `POST /backend/api/orders/update_status.php` - Update order status (Admin)

## Security Features

- Password hashing using bcrypt
- JWT-based authentication
- Prepared statements for SQL queries
- Input validation and sanitization
- CORS headers configuration
- Role-based access control

## Order Status Workflow

1. **Pending** - Order placed by customer
2. **Confirmed** - Order confirmed by admin
3. **Preparing** - Order being prepared
4. **Out for Delivery** - Order dispatched
5. **Completed** - Order delivered
6. **Cancelled** - Order cancelled (stock restored)

## Usage Guide

### For Customers
1. Register a new account or login
2. Browse products and add items to cart
3. Proceed to checkout and enter delivery details
4. Track order status from "My Orders" page
5. View order history and details

### For Administrators
1. Login with admin credentials
2. View dashboard with analytics
3. Manage products (add, edit, delete, update stock)
4. Manage orders (view details, update status)
5. Monitor low-stock products

## Troubleshooting

### Common Issues

**Issue**: Cannot connect to database
- **Solution**: Ensure MySQL is running in XAMPP and credentials in `database.php` are correct

**Issue**: 404 errors for API calls
- **Solution**: Check that the project is in the correct htdocs directory and Apache is running

**Issue**: Login not working
- **Solution**: Ensure the database is properly set up with the schema.sql file

**Issue**: CORS errors
- **Solution**: Access the application through `http://localhost` (not `file://`)

## Browser Compatibility

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Development Notes

- The system uses LocalStorage for cart management
- JWT tokens expire after 24 hours
- All API responses are in JSON format
- Images can be added via URL (external hosting recommended)

## Future Enhancements

- Payment gateway integration
- Email notifications
- Product reviews and ratings
- Advanced analytics and reporting
- Multi-language support
- Mobile application

## License

This project is open-source and available for educational purposes.

## Support

For issues or questions, please refer to the documentation or contact the development team.

---

**Note**: This is a development system. For production use, implement additional security measures such as HTTPS, environment variables for sensitive data, and proper error logging.
