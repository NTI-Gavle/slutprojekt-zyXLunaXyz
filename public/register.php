<?php

$pageTitle = 'Register - Z';

require_once __DIR__ . '/../database/user_queries.php';
require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $displayName = trim($_POST['display_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($displayName === '' || $username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (userExists($dbconn, $username, $email)) {
        $error = 'Username or email already exists.';
    } else {
        if (createUser($dbconn, $username, $email, $password, $displayName)) {
            $success = 'Account created successfully. You can now log in.';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
    }
}
?>

<main class="min-h-screen flex items-center justify-center px-6 py-10">
    <section class="w-full max-w-6xl min-h-[620px] border border-neutral-900 bg-black grid grid-cols-1 md:grid-cols-2 items-center px-8 md:px-20 py-12">

        <div class="hidden md:flex items-center justify-center">
            <h1 class="z-logo-font text-[18rem] leading-none text-white select-none">
                Z
            </h1>
        </div>

        <div class="flex justify-center">
            <div class="w-full max-w-sm">
                <p class="text-neutral-300 text-center mb-8">Create your account</p>

                <?php if ($error): ?>
                    <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300 text-sm">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="mb-4 rounded-xl border border-green-500/40 bg-green-500/10 px-4 py-3 text-green-300 text-sm">
                        <?= e($success) ?>
                    </div>
                <?php endif; ?>

                <button
                    type="button"
                    data-auth-toggle="registerDropdown"
                    class="z-auth-button bg-white text-black"
                >
                    Create an account
                </button>

                <form
                    id="registerDropdown"
                    method="post"
                    class="hidden z-auth-dropdown mt-4 space-y-4"
                >
                    <input class="z-input" type="text" name="display_name" placeholder="Display name" required>
                    <input class="z-input" type="text" name="username" placeholder="Username" required>
                    <input class="z-input" type="email" name="email" placeholder="Email" required>
                    <input class="z-input" type="password" name="password" placeholder="Password" required>
                    <input class="z-input" type="password" name="confirm_password" placeholder="Confirm password" required>

                    <button class="z-auth-button bg-sky-500 text-white" type="submit">
                        Register
                    </button>
                </form>

                <div class="my-6 flex items-center gap-4">
                    <div class="h-px flex-1 bg-neutral-700"></div>
                    <span class="text-neutral-400 text-sm">OR</span>
                    <div class="h-px flex-1 bg-neutral-700"></div>
                </div>

                <button class="z-auth-button bg-[#5865F2] text-white flex items-center justify-center gap-3" type="button">
                    <svg class="z-discord-icon" viewBox="0 0 127.14 96.36" aria-hidden="true">
                        <path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a68.68,68.68,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1A105.25,105.25,0,0,0,126.6,80.22h0C129.24,52.84,122.09,29.11,107.7,8.07ZM42.45,65.69C36.18,65.69,31,60,31,53s5-12.74,11.43-12.74S54,46,53.89,53,48.84,65.69,42.45,65.69Zm42.24,0C78.41,65.69,73.25,60,73.25,53s5-12.74,11.44-12.74S96.23,46,96.12,53,91.08,65.69,84.69,65.69Z"/>
                    </svg>
                    <span>Register with Discord</span>
                </button>

                <p class="text-center text-neutral-400 mt-6">
                    Already have an account?
                    <a href="login.php" class="text-sky-400 hover:underline">Login</a>
                </p>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>