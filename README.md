PHP API for Customer Management
Overview
This project is a simple RESTful API built using PHP and MySQL that allows for managing customer data. The operations supported include creating, reading, updating, and deleting customer records in a database. The API responds with JSON formatted messages and supports CORS for cross-origin requests.

File Structure
- dbconn.php: This file establishes a connection to the MySQL database. It sets up database credentials and initializes the connection.

- function.php: This file contains various helper functions:

- jsonResponse(): Returns a formatted JSON response.
- validateCustomerData(): Checks customer data for validity.
- getCustomerList(): Retrieves all customers from the database.
- storeCustomer(): Validates and stores a new customer in the database.
- updateCustomer(): Updates an existing customer's details.
- deleteCustomer(): Deletes a customer from the database.
- getCustomerDetails(): Retrieves details of a specific customer.
- api.php: This is the main entry point for the API. It processes incoming requests and routes them to the appropriate functions based on the specified action.

API Endpoints
1. Get Customer List
Method: GET
URL: http://yourdomain/api.php?action=get
Description: Retrieves a list of all customers.
Response:
Success:
json
{
  "status": 200,
  "message": "Success",
  "data": [ ... ] // List of customers
}

No Data:
json
{
  "status": 404,
  "message": "No data found"
}

2. Get Customer Details
Method: GET
URL: http://yourdomain/api.php?action=get&id={customerId}
Description: Retrieves detailed information about a specific customer.
Response:
Success:
json
{
  "status": 200,
  "message": "Success",
  "data": { ... } // Customer details
}

Not Found:
json
{
  "status": 404,
  "message": "Customer ID not found"
}

3. Create Customer
Method: POST
URL: http://yourdomain/api.php?action=create
Body (JSON):
json
{
  "name": "willbe",
  "email": "willbe.test@gmail.com  ",
  "phone": "0987654321"
}

Description: Creates a new customer after validating the data.
Response:
Success:
json
{
  "status": 201,
  "message": "Customer created"
}

Validation Error:
json
{
  "status": 400,
  "message": "Customer name is required, Email already exists..."
}

4. Update Customer
Method: POST
URL: http://yourdomain/api.php?action=update&id={customerId}
Body (JSON):
json
{
  "name": "tester",
  "email": "tester.test@gmail.com",
  "phone": "0123456789"
}

Description: Updates an existing customer's information.
Response:
Success:
json
{
  "status": 200,
  "message": "Customer updated"
}

Not Found:
json
{
  "status": 404,
  "message": "Customer ID not found"
}

Validation Error:
json
{
  "status": 400,
  "message": "Customer name is required, Email already exists..."
}

5. Delete Customer
Method: DELETE
URL: http://yourdomain/api.php?action=delete&id={customerId}
Description: Deletes a customer from the database.
Response:
Success:
json
{
  "status": 200,
  "message": "Customer deleted"
}

Not Found:
json
{
  "status": 404,
  "message": "Customer ID not found"
}

Using Postman
Install Postman: Download and install Postman from the official website.

Create a New Request:

Launch Postman and click on "New" to create a new request.
Choose the request method (GET, POST, DELETE) based on the operation.
Set the URL:

Input the URL of your API endpoint, e.g., http://yourdomain/api.php?action=get for retrieving the customer list.
Set Headers (for POST requests):

Click on the "Headers" tab and add:
Content-Type: application/json
Set Request Body (for POST requests):

Click on the "Body" tab.
Select "raw" and choose "JSON" from the dropdown.
Input your JSON data.
Send the Request: Click the "Send" button and review the response in the lower section of Postman.

This comprehensive description provides a good overview of the API functionality and how to use Postman for testing.
