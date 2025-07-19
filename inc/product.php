<?php
class Product
{
    private $conn;
    private $table_name = "products";

    public $id;
    public $order_id;
    public $product_name;
    public $price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create product
    public function create()
    {
        // $query = "INSERT INTO " . $this->table_name . " (order_id, product_name, price) VALUES (:order_id, :product_name, :price)";
        // $stmt = $this->conn->prepare($query);
        $query = "INSERT INTO products (order_id, product_name, price) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // $stmt->bindParam(":order_id", $this->order_id);
        // $stmt->bindParam(":product_name", $this->product_name);
        // $stmt->bindParam(":price", $this->price);

        $stmt->bind_param("isd", $this->order_id, $this->product_name, $this->price);
        return $stmt->execute();
    }

    // // Fetch products for a specific order
    // public function readByOrder($order_id)
    // {
    //     $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = :order_id";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(":order_id", $order_id);
    //     $stmt->execute();

    //     return $stmt;
    // }

    public function readByOrder($order_id)
    {
        $query = "SELECT id, product_name, price FROM " . $this->table_name . " WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result(); // This returns a mysqli_result
    }
}