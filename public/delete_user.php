<?php

require_once __DIR__ . '/../database/admin_queries.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requireLogin();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$userId = (int) ($_POST['user_id'] ?? 0);

if ($userId <= 0) {
    redirect('index.php');
}

if ($userId === (int) $_SESSION['user_id']) {
    redirect('index.php');
}

deleteUserById($dbconn, $userId);

redirect('index.php');