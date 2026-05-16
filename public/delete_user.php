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

$currentRole = $_SESSION['role'] ?? 'user';
$targetRole = getUserRoleById($dbconn, $userId);

if ($targetRole === null) {
    redirect('index.php');
}

if ($currentRole === 'owner') {
    if ($targetRole === 'owner') {
        redirect('index.php');
    }

    deleteUserById($dbconn, $userId);
    redirect('index.php');
}

if ($currentRole === 'admin') {
    if ($targetRole !== 'user') {
        redirect('index.php');
    }

    deleteUserById($dbconn, $userId);
    redirect('index.php');
}

redirect('index.php');