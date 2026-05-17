<?php 
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function isAdmin(): bool
{
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' ||  $_SESSION['role'] === 'owner');
}

function requireAdmin(): void
{
    if (!canModerateUsers()) {
        redirect('index.php');
    }
}

function isOwner(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'owner';
}

function canModerateUsers(): bool
{
    return isAdmin() || isOwner();
}
?>

