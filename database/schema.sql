-- CartLink System Database Schema
-- MySQL Database for Web-Based Food Ordering System

CREATE DATABASE IF NOT EXISTS cartlink_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cartlink_db;

-- Users table (both customers and admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table (Food categories)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table (Menu items / Food items)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reference_number VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'out_for_delivery', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_address TEXT NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    payment_method ENUM('cod', 'credit_card', 'debit_card', 'paypal', 'bank_transfer') DEFAULT 'cod',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_reference (reference_number),
    INDEX idx_status (status),
    INDEX idx_payment_method (payment_method),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin logs table
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_admin (admin_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - hashed with bcrypt)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@cartlink.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');

-- Insert sample categories (Food categories)
INSERT INTO categories (name, description) VALUES
('Appetizers', 'Delicious starters and appetizers'),
('Main Course', 'Main dishes and entrees'),
('Desserts', 'Sweet treats and desserts'),
('Beverages', 'Drinks and refreshments'),
('Salads', 'Fresh and healthy salads');

-- Insert sample products (Food items)
INSERT INTO products (category_id, name, description, price, stock, status) VALUES
(1, 'Spring Rolls', 'Crispy vegetable spring rolls served with sweet chili sauce', 6.99, 50, 'active'),
(1, 'Chicken Wings', 'Spicy buffalo chicken wings with ranch dressing', 9.99, 40, 'active'),
(2, 'Grilled Salmon', 'Fresh Atlantic salmon grilled to perfection with herbs', 18.99, 30, 'active'),
(2, 'Beef Burger', 'Juicy beef burger with lettuce, tomato, and special sauce', 12.99, 50, 'active'),
(2, 'Pasta Carbonara', 'Creamy pasta with bacon and parmesan cheese', 14.99, 45, 'active'),
(3, 'Chocolate Lava Cake', 'Warm chocolate cake with molten center and vanilla ice cream', 7.99, 35, 'active'),
(3, 'Tiramisu', 'Classic Italian dessert with coffee and mascarpone', 6.99, 40, 'active'),
(4, 'Fresh Orange Juice', 'Freshly squeezed orange juice', 4.99, 100, 'active'),
(4, 'Iced Coffee', 'Cold brew coffee served over ice', 3.99, 80, 'active'),
(5, 'Caesar Salad', 'Crisp romaine lettuce with Caesar dressing and croutons', 8.99, 60, 'active'),
(5, 'Greek Salad', 'Fresh vegetables with feta cheese and olives', 9.99, 55, 'active');
