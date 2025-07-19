<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db.php';

$database = new Database();
$db = $database->getConnection();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the request data if any
$requestData = json_decode(file_get_contents("php://input"), true);

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getUser($db, $_GET['id']);
        } else {
            getUsers($db);
        }
        break;

    case 'POST':
        createUser($db, $requestData);
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            updateUser($db, $_GET['id'], $requestData);
        } else {
            echo json_encode(['message' => 'User ID is required']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteUser($db, $_GET['id']);
        } else {
            echo json_encode(['message' => 'User ID is required']);
        }
        break;

    default:
        echo json_encode(['message' => 'Request method not supported']);
}

// Function to get all users
function getUsers($db) {
    $query = "SELECT * FROM users";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

// Function to get a specific user
function getUser($db, $id) {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 0,1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user);
    } else {
        echo json_encode(['message' => 'User not found']);
    }
}

// Function to create a new user
function createUser($db, $data) {
    if (!empty($data['name']) && !empty($data['email'])) {
        $query = "INSERT INTO users SET name = :name, email = :email";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'User created successfully']);
        } else {
            echo json_encode(['message' => 'User could not be created']);
        }
    } else {
        echo json_encode(['message' => 'Incomplete data']);
    }
}

// Function to update a user
function updateUser($db, $id, $data) {
    if (!empty($data['name']) && !empty($data['email'])) {
        $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'User updated successfully']);
        } else {
            echo json_encode(['message' => 'User could not be updated']);
        }
    } else {
        echo json_encode(['message' => 'Incomplete data']);
    }
}

// Function to delete a user
function deleteUser($db, $id) {
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'User deleted successfully']);
    } else {
        echo json_encode(['message' => 'User could not be deleted']);
    }
}
?>