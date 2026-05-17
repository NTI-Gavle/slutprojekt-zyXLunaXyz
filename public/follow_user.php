<?php

require_once __DIR__ . '/../database/follow_queries.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$currentUserId = (int) $_SESSION['user_id'];
$targetUserId = (int) ($_POST['user_id'] ?? 0);
$redirectTo = $_POST['redirect_to'] ?? 'index.php';

if ($targetUserId <= 0 || $targetUserId === $currentUserId) {
    redirect($redirectTo);
}

toggleFollow($dbconn, $currentUserId, $targetUserId);

redirect($redirectTo);