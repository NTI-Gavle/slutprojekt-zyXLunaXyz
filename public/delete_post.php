<?php

require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$postId = (int) ($_POST['post_id'] ?? 0);

if ($postId <= 0) {
    redirect('index.php');
}

deletePost(
    $dbconn,
    $postId,
    (int) $_SESSION['user_id'],
    isAdmin()
);

redirect('index.php');