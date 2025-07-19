<?php
class Order
{
    private $conn;
    private $table_name = "orders";

    public $id;
    public $order_number;
    public $customer_name;
    public $products = [];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create order
    public function create()
    {
       // $query = "INSERT INTO " . $this->table_name . " (order_number, customer_name) VALUES (:order_number, :customer_name)";
        $query = "INSERT INTO ". $this->table_name . "(order_number, customer_name) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ss", $this->order_number, $this->customer_name);
        // $stmt->bindParam(":order_number", $this->order_number);
        // $stmt->bindParam(":customer_name", $this->customer_name);

        if ($stmt->execute()) {
            $this->id = $stmt->insert_id ?: $this->conn->insert_id;
            return true;
        }

        return false;
    }

    // Fetch all orders with their products
    // public function read()
    // {
    //     $query = "SELECT * FROM " . $this->table_name;
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();

    //     return $stmt;
    // }

    public function read()
    {
        $query = "SELECT id, order_number, customer_name FROM " . $this->table_name;
        $result = $this->conn->query($query);
        return $result;
    }


    // Fetch a single order
    public function readOne($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->order_number = $row['order_number'];
            $this->customer_name = $row['customer_name'];
        }

        return $row;
    }
}
