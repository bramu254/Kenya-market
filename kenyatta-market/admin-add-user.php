<?php
session_start();
require 'db.php';

// Only allow admin to add users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $county = $conn->real_escape_string($_POST['county']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']);

    $sql = "INSERT INTO users (first_name, last_name, email, phone, county, password, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone, $county, $password, $role);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?msg=User+added+successfully");
        exit();
    } else {
        echo "Error adding user: " . $conn->error;
    }
}
?>
