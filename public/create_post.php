<?php

require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$content = trim($_POST['content'] ?? '');

if ($content === '') {
    redirect('index.php');
}

if (mb_strlen($content) > 280) {
    redirect('index.php');
}

createPost($dbconn, (int) $_SESSION['user_id'], $content);

redirect('index.php');