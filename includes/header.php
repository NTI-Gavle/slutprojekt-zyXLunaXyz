<?php 
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? "Z"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/styles.css?v<?= time()?>">
    <script src="js/app.js?v=<?= time() ?>" defer></script>
</head>
<body="bg-black text-white min-h-screen">
    
