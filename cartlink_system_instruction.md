# 🚀 **Full Prompt Script / Detailed Description (Web-Based Ordering System --- PHP API + MySQL in XAMPP + HTML/JS Frontend)**

> **Use this prompt to let an AI system build, scaffold, or enhance your
> ordering system project.**

## PROJECT OVERVIEW

Build a fully operational **web-based ordering system** anchored on:

-   **Frontend:** HTML, CSS, JavaScript
-   **Backend:** PHP API
-   **Database:** MySQL running in XAMPP
-   **Architecture:** RESTful API with clean separation between UI and
    backend
-   **Deployment:** Localhost environment under `htdocs/`

The system must deliver a seamless customer experience supported by
enterprise-grade admin controls, order tracking, and inventory
management.

## CORE SYSTEM OBJECTIVES

1.  Deliver a user-friendly online ordering platform
2.  Provide tools for administrators to manage inventory, products, and
    incoming orders
3.  Maintain real-time order tracking for customers
4.  Enforce secure authentication and authorization flows
5.  Maintain clean, modular, scalable PHP API endpoints
6.  Use MySQL as the authoritative data source

## FEATURE SET (FULL LIST)

### 1. User & Account Management

-   Customer registration & login
-   Admin login
-   JWT or token-based authentication
-   Session security & validation
-   Role-based access control
-   Password hashing
-   Profile viewing

### 2. Product Catalog

-   Product listing page
-   Product search and filtering
-   Product detail view
-   Stock display
-   Category management (admin)

### 3. Shopping Cart

-   Add/remove products
-   Update quantities
-   Cart summary
-   LocalStorage or API cart storage
-   Stock validation

### 4. Checkout Workflow

-   Address and contact input
-   Order summary preview
-   Real-time stock check
-   Auto stock deduction
-   Reference number generation

### 5. Order Management (Customer)

-   View orders
-   Track status
-   Reorder feature

Order lifecycle: **Pending → Confirmed → Preparing → Out for Delivery →
Completed → Cancelled**

### 6. Order Management (Admin)

-   View all orders
-   Update status
-   Assign riders (optional)
-   View financial totals
-   Search orders

### 7. Product Management (Admin)

-   Add, edit, delete products
-   Upload images
-   Manage price, category, description
-   Stock updates
-   Low-stock report

### 8. Inventory & Stock Controls

-   Auto deduction
-   Restore stock on cancellation
-   Restock module
-   Stock activity logs

### 9. Analytics Dashboard (Admin)

-   Daily/monthly sales
-   Best sellers
-   Inventory insights
-   Order charts
-   Low-stock alerts

### 10. Security

-   API request validation
-   Sanitization
-   CSRF protection
-   CORS control
-   Token expiration

## DATABASE REQUIREMENTS (MySQL)

Tables:

-   `users`
-   `products`
-   `categories`
-   `orders`
-   `order_items`
-   `admin_logs`

Use InnoDB + foreign keys.

## TECHNICAL REQUIREMENTS

### Frontend

-   Bootstrap or Tailwind
-   Axios
-   Modular JS
-   Responsive UI

### Backend

-   Clean folder structure
-   JSON responses
-   Prepared statements
-   Central DB connection
-   JSON error reporting

API endpoints included for:

-   Auth
-   Products
-   Categories
-   Orders

## UI MODULE REQUIREMENTS

### Customer UI

-   Home
-   Cart
-   Checkout
-   Order tracking
-   Login/Register

### Admin UI

-   Login
-   Dashboard
-   Product Manager
-   Order Manager
-   Category Manager
-   Settings

## OUTPUT EXPECTATION

The AI should generate:

1.  Folder structure
2.  API architecture
3.  MySQL schema
4.  Frontend skeleton
5.  Dashboard logic
6.  API connectivity
7.  Order lifecycle
8.  Error handling
9.  Security layers
10. Polished UI

## END OF PROMPT
