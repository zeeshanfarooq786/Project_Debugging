<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 1. Establish Secure Connection
    $conn = new mysqli('localhost', 'root', '', 'inventory_db');

    // 2. Input Validation (Sanitize the GET parameter)
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        throw new Exception("Invalid or missing ID.");
    }

    // 3. Prepared Statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT name, price FROM items WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    
    // 4. Fetch Result
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        echo "Item: " . htmlspecialchars($item['name']) . " | Price: " . $item['price'];
    } else {
        echo "No record found.";
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Log error and show generic message to user
    error_log($e->getMessage());
    die("A technical error occurred. Please try again later.");
}