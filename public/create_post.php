<?php

require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

function uploadPostImage(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return null;
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $mimeType = mime_content_type($file['tmp_name']);

    if (!isset($allowedTypes[$mimeType])) {
        return null;
    }

    $extension = $allowedTypes[$mimeType];
    $fileName = uniqid('post_', true) . '.' . $extension;

    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR;

    if (file_exists($uploadDir) && !is_dir($uploadDir)) {
        return null;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadPath = $uploadDir . $fileName;
    $publicPath = 'uploads/posts/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return null;
    }

    return $publicPath;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$content = trim($_POST['content'] ?? '');
$imageUrl = uploadPostImage($_FILES['post_image'] ?? []);

if ($content === '' && $imageUrl === null) {
    redirect('index.php');
}

if (mb_strlen($content) > 280) {
    redirect('index.php');
}

createPost($dbconn, (int) $_SESSION['user_id'], $content, $imageUrl);

redirect('index.php');