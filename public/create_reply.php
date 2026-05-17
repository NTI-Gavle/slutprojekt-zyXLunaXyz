<?php

require_once __DIR__ . '/../database/reply_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$postId = (int) ($_POST['post_id'] ?? 0);
$content = trim($_POST['content'] ?? '');

if ($postId <= 0) {
    redirect('index.php');
}

if ($content === '') {
    redirect('post.php?id=' . $postId);
}

if (mb_strlen($content) > 280) {
    redirect('post.php?id=' . $postId);
}

createReply($dbconn, $postId, (int) $_SESSION['user_id'], $content);

redirect('post.php?id=' . $postId);