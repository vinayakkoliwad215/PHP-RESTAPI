<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../inc/database.php';
include_once '../class/employees.php';


$database = new Database();
$db = $database->getConnection();
$empObj = new Employees($db);

$employeeTable = 'employees';

$requertMethod = $_SERVER['REQUEST_METHOD'];

if ($requertMethod == 'GET') {
    if (isset($_GET['id'])) {
        $empId = $_GET['id'];
        $getEmployeDetails = $empObj->selectDataById($employeeTable, $empId);
    } else {
        $getEmployeDetails = $empObj->fetchAllData($employeeTable);
    }
    echo $getEmployeDetails;
}else{
    $data = [
        'status' => 405,
        'message' => $requertMethod. ' Method now allowed',
    ];
    header("HTTP/1.0 405 Method now allowed");
    echo json_encode($data);
}

?>