<?php
/**
 * Orders API - Create Order
 * POST /backend/api/orders/create.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../utils/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

// Verify authentication
$auth = verifyAuth();
if (!$auth) {
    sendResponse(false, 'Unauthorized access', null, 401);
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->items) || empty($data->delivery_address) || empty($data->contact_phone)) {
    sendResponse(false, 'Items, delivery address, and contact phone are required', null, 400);
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Start transaction
    $db->beginTransaction();
    
    // Calculate total and validate stock
    $totalAmount = 0;
    $orderItems = [];
    
    foreach ($data->items as $item) {
        // Get product details
        $query = "SELECT id, name, price, stock FROM products WHERE id = :id AND status = 'active'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $item->product_id);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            $db->rollBack();
            sendResponse(false, 'Product not found or inactive', null, 400);
        }
        
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check stock
        if ($product['stock'] < $item->quantity) {
            $db->rollBack();
            sendResponse(false, "Insufficient stock for product: {$product['name']}", null, 400);
        }
        
        $subtotal = $product['price'] * $item->quantity;
        $totalAmount += $subtotal;
        
        $orderItems[] = [
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'product_price' => $product['price'],
            'quantity' => $item->quantity,
            'subtotal' => $subtotal
        ];
    }
    
    // Generate reference number
    $referenceNumber = generateReferenceNumber();
    
    // Create order
    $query = "INSERT INTO orders (user_id, reference_number, total_amount, status, delivery_address, contact_phone, notes) 
              VALUES (:user_id, :reference_number, :total_amount, 'pending', :delivery_address, :contact_phone, :notes)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $auth['user_id']);
    $stmt->bindParam(':reference_number', $referenceNumber);
    $stmt->bindParam(':total_amount', $totalAmount);
    $stmt->bindParam(':delivery_address', $data->delivery_address);
    $stmt->bindParam(':contact_phone', $data->contact_phone);
    
    $notes = $data->notes ?? null;
    $stmt->bindParam(':notes', $notes);
    
    $stmt->execute();
    $orderId = $db->lastInsertId();
    
    // Insert order items and update stock
    foreach ($orderItems as $item) {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) 
                  VALUES (:order_id, :product_id, :product_name, :product_price, :quantity, :subtotal)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':product_id', $item['product_id']);
        $stmt->bindParam(':product_name', $item['product_name']);
        $stmt->bindParam(':product_price', $item['product_price']);
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':subtotal', $item['subtotal']);
        $stmt->execute();
        
        // Update stock
        $query = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':product_id', $item['product_id']);
        $stmt->execute();
    }
    
    // Commit transaction
    $db->commit();
    
    sendResponse(true, 'Order created successfully', [
        'order_id' => $orderId,
        'reference_number' => $referenceNumber,
        'total_amount' => $totalAmount
    ], 201);
} catch(Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    sendResponse(false, 'Failed to create order: ' . $e->getMessage(), null, 500);
}
?>
