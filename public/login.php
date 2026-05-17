<?php

$pageTitle = 'Login - Z';

require_once __DIR__ . '/../database/user_queries.php';

require_once __DIR__ . '/../includes/header.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Enter username/email and password.';
    } else {
        $user = findUserByUsernameOrEmail($dbconn, $login);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['display_name'] = $user['display_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = $user['profile_image'];

            redirect('index.php');
        } else {
            $error = 'Wrong username/email or password.';
        }
    }
}
?>

<main class="min-h-screen flex items-center justify-center px-6 py-10">
    <section class="w-full max-w-6xl min-h-[620px] border border-neutral-900 bg-black grid grid-cols-1 md:grid-cols-2 items-center px-8 md:px-20 py-12">

        <!-- Left big logo -->
        <div class="hidden md:flex items-center justify-center">
            <h1 class="z-logo-font text-[18rem] leading-none text-white select-none">
                Z
            </h1>
        </div>

        <!-- Right login area -->
        <div class="flex justify-center">
            <div class="w-full max-w-sm">
            <p class="text-neutral-300 text-center mb-8">Login to your account</p>

                <?php if ($error): ?>
                    <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300 text-sm">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

               <button
                    type="button"
                    data-auth-toggle="loginDropdown"
                    class="z-auth-button bg-white text-black"
                >
                    Sign in
                </button>

                <form
                    id="loginDropdown"
                    method="post"
                    class="hidden z-auth-dropdown mt-4 space-y-4"
                >
                    <input class="z-input" type="text" name="login" placeholder="Username or email" required>
                    <input class="z-input" type="password" name="password" placeholder="Password" required>

                    <button class="z-auth-button bg-sky-500 text-white" type="submit">
                        Login
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
                    <span>Login with Discord</span>
                </button>

                <p class="text-center text-neutral-400 mt-6">
                    Don't have an account?
                    <a href="register.php" class="text-sky-400 hover:underline">Register</a>
                </p>
            </div>
        </div>
    </section>
</main>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>