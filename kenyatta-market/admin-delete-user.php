<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];

    // Don't allow deleting other admins
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $role = $stmt->get_result()->fetch_assoc()['role'] ?? '';

    if ($role !== 'admin') {
        $conn->query("DELETE FROM users WHERE id = $userId");
    }
}

header('Location: admin-dashboard.php');
exit;
