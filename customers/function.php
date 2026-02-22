<?php

require_once '../inc/dbconn.php';

function jsonResponse($status, $message, $data = null)
{
  http_response_code($status);
  $response = ['status' => $status, 'message' => $message];

  if ($data !== null) {
    $response['data'] = $data;
  }

  return json_encode($response);
}

function validateCustomerData($data, $isUpdate = false)
{
  global $conn;
  $errors = [];

  if (empty($data['name'])) {
    $errors[] = "Customer name is required";
  }

  if (empty($data['email'])) {
    $errors[] = "Email is required";
  }

  if (empty($data['phone'])) {
    $errors[] = "Phone is required";
  }

  if (!$isUpdate) {
    if (!empty($data['email'])) {
      $email = mysqli_real_escape_string($conn, $data['email']);
      $checkEmailQuery = "SELECT * FROM customers WHERE email = '$email'";
      $checkEmailResult = mysqli_query($conn, $checkEmailQuery);
      if (mysqli_num_rows($checkEmailResult) > 0) {
        $errors[] = "Email already exists";
      }
    }

    if (!empty($data['name'])) {
      $name = mysqli_real_escape_string($conn, $data['name']);
      $checkNameQuery = "SELECT * FROM customers WHERE name = '$name'";
      $checkNameResult = mysqli_query($conn, $checkNameQuery);
      if (mysqli_num_rows($checkNameResult) > 0) {
        $errors[] = "Name already exists";
      }
    }

    if (!empty($data['phone'])) {
      $phone = mysqli_real_escape_string($conn, $data['phone']);
      $checkPhoneQuery = "SELECT * FROM customers WHERE phone = '$phone'";
      $checkPhoneResult = mysqli_query($conn, $checkPhoneQuery);
      if (mysqli_num_rows($checkPhoneResult) > 0) {
        $errors[] = "Phone already exists";
      }
    }
  }

  return $errors;
}

function getCustomerList()
{
  global $conn;
  $sql = "SELECT * FROM customers";
  $query = mysqli_query($conn, $sql);

  if ($query) {
    $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
    return $res ? jsonResponse(200, "Success", $res) : jsonResponse(404, "No data found");
  }
  return jsonResponse(500, "Internal Server Error");
}

function storeCustomer($data)
{
  $errors = validateCustomerData($data);
  if (!empty($errors)) {
    return jsonResponse(400, implode(", ", $errors));
  }
  return createCustomer($data);
}

function createCustomer($data)
{
  global $conn;
  $name = mysqli_real_escape_string($conn, $data['name']);
  $email = mysqli_real_escape_string($conn, $data['email']);
  $phone = mysqli_real_escape_string($conn, $data['phone']);

  $sql = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";

  return mysqli_query($conn, $sql) ? jsonResponse(201, "Customer created") : jsonResponse(500, "Internal Server Error");
}

function updateCustomer($id, $data)
{
  global $conn;
  $responseStatus = 200;
  $responseMessage = "Customer updated";

  $existsQuery = "SELECT * FROM customers WHERE id = " . intval($id);
  $existsResult = mysqli_query($conn, $existsQuery);

  if (mysqli_num_rows($existsResult) == 0) {
    $responseStatus = 404;
    $responseMessage = "Customer ID not found";
  } else {
    $errors = validateCustomerData($data, true);
    if (!empty($errors)) {
      $responseStatus = 400;
      $responseMessage = implode(", ", $errors);
    } else {
      $name = mysqli_real_escape_string($conn, $data['name']);
      $email = mysqli_real_escape_string($conn, $data['email']);
      $phone = mysqli_real_escape_string($conn, $data['phone']);
      $sql = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id = $id";

      if (!mysqli_query($conn, $sql)) {
        $responseStatus = 500;
        $responseMessage = "Internal Server Error";
      }
    }
  }

  return jsonResponse($responseStatus, $responseMessage);
}

function deleteCustomer($id)
{
  global $conn;
  $responseStatus = 200;
  $responseMessage = "Customer deleted";

  $existsQuery = "SELECT * FROM customers WHERE id = " . intval($id);
  $existsResult = mysqli_query($conn, $existsQuery);

  if (mysqli_num_rows($existsResult) == 0) {
    $responseStatus = 404;
    $responseMessage = "Customer ID not found";
  } else {
    $sql = "DELETE FROM customers WHERE id = $id";
    if (!mysqli_query($conn, $sql)) {
      $responseStatus = 500;
      $responseMessage = "Internal Server Error";
    }
  }

  return jsonResponse($responseStatus, $responseMessage);
}

function getCustomerDetails($id)
{
  global $conn;
  $responseStatus = 200;
  $responseMessage = "Success";
  $responseData = null;

  $sql = "SELECT * FROM customers WHERE id = " . intval($id);
  $query = mysqli_query($conn, $sql);

  if ($query) {
    $res = mysqli_fetch_assoc($query);
    if ($res) {
      $responseData = $res;
    } else {
      $responseStatus = 404;
      $responseMessage = "Customer ID not found";
    }
  } else {
    $responseStatus = 500;
    $responseMessage = "Internal Server Error";
  }

  return jsonResponse($responseStatus, $responseMessage, $responseData);
}
