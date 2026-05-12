<?php
$pageTitle = "Z"; 
require_once __DIR__ . '/../includes/header.php';

if(!isLoggedIn()) {
    redirect('login.php');
}
?>

<main class="min-h-screen flex items-center justify-center px-4">
    <section class="text-center">
        <h1 class="text-5xl font-bold mb-4">Welcome to Z</h1>
        <p class="text-neutral-400 mb-6">You are logged in as @<?= e($_SESSION['username']) ?></p>
        <a href="logout.php" class="inline-block rounded-full bg-sky-500 px-6 py-3 font-bold hover:bg-sky-600 transition">
            Logout
        </a>
    </section>
</main>

<?php
require_once __DIR__ . '/../includes/footer.php';