<?php

require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$postId = (int) ($_POST['post_id'] ?? 0);
$userId = (int) ($_SESSION['user_id'] ?? 0);

if ($postId <= 0 || $userId <= 0) {
    redirect('index.php');
}

toggleLike($dbconn, $postId, $userId);

redirect('index.php');