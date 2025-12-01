-- Add payment method support to CartLink System
-- Run this migration to add payment method column to orders table

USE cartlink_db;

-- Add payment_method column to orders table
ALTER TABLE orders 
ADD COLUMN payment_method ENUM('cod', 'credit_card', 'debit_card', 'paypal', 'bank_transfer') 
DEFAULT 'cod' 
AFTER contact_phone;

-- Update existing orders to have COD as default payment method
UPDATE orders SET payment_method = 'cod' WHERE payment_method IS NULL;

-- Add index for payment method for faster queries
ALTER TABLE orders ADD INDEX idx_payment_method (payment_method);
