<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
$action = $_GET['action'] ?? null;

switch ($action) {
    case 'get':
        $customerId = $_GET['id'] ?? null;
        echo $customerId ? getCustomerDetails($customerId) : getCustomerList();
        break;

    case 'create':
        $data = $requestMethod === 'POST' ? json_decode(file_get_contents("php://input"), true) : parseGetParams(['name', 'email', 'phone']);
        echo storeCustomer($data);
        break;

    case 'update':
        $customerId = $_GET['id'] ?? null;
        $data = $requestMethod === 'POST' ? json_decode(file_get_contents("php://input"), true) : parseGetParams(['name', 'email', 'phone']);
        echo $customerId ? updateCustomer($customerId, $data) : jsonResponse(400, "Customer ID required");
        break;

    case 'delete':
        $customerId = $_GET['id'] ?? null;
        echo $customerId ? deleteCustomer($customerId) : jsonResponse(400, "Customer ID required");
        break;

    default:
        echo jsonResponse(400, "Invalid action");
        break;
}

function parseGetParams($fields) {
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_GET[$field] ?? null;
    }
    return $data;
}