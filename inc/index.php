<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'Database.php';
require_once 'Order.php';
require_once 'Product.php';

$database = new Database();
$db = $database->getConnection();

// Fetch the request method
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getOrder($_GET['id'], $db);
        } else {
            getOrders($db);
        }
        break;

    case 'POST':
        createOrder($db);
        break;

    default:
        echo json_encode(['message' => 'Invalid Request']);
        break;
}

// Fetch all orders with products
function getOrders($db)
{
    $order = new Order($db);
    $stmt = $order->read();
    $orders = [];

    while ($row = $stmt->fetch_assoc()) {
        $products = new Product($db);
        $product_stmt = $products->readByOrder($row['id']);

        $order_products = [];
        while ($prod = $product_stmt->fetch_assoc()) {
            $order_products[] = $prod;
        }

        $row['products'] = $order_products;
        $orders[] = $row;
    }

    echo json_encode($orders);
}

// Fetch a single order
function getOrder($id, $db)
{
    $order = new Order($db);
    if ($order->readOne($id)) {
        $products = new Product($db);
        $product_stmt = $products->readByOrder($id);
        $order_products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'products' => $order_products
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['message' => 'Order not found']);
    }
}

// Create an order
function createOrder($db)
{
    $data = json_decode(file_get_contents("php://input"));

    if (!$data) {
        echo json_encode(['message' => 'Invalid or empty JSON input']);
        return;
    }

    $order = new Order($db);
    $order->order_number = $data->order_number ?? null;
    $order->customer_name = $data->customer_name ?? null;

    if ($order->create()) {
        $products = new Product($db);

        foreach ($data->products as $product) {
            $products->order_id = $order->id; // make sure $order->id is set after creation
            $products->product_name = $product->product_name ?? null;
            $products->price = $product->price ?? 0;
            $products->create();
        }

        echo json_encode(['message' => 'Order created successfully']);
    } else {
        echo json_encode(['message' => 'Failed to create order']);
    }
}

?>