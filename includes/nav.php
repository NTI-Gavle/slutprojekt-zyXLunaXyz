<nav class="hidden lg:flex fixed left-0 top-0 h-screen w-72 border-r border-neutral-800 bg-black px-6 py-5 flex-col justify-between">
    <div>
        <a href="index.php" class="z-logo-font block text-6xl leading-none mb-10 hover:text-sky-400 transition">
            Z
        </a>

        <ul class="space-y-2 text-xl">

            <li>
                <a href="index.php" class="z-nav-link">
                    <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 10.75L12 3l9 7.75V21a1 1 0 0 1-1 1h-5.5a1 1 0 0 1-1-1v-5.5h-3V21a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.75z"/>
                    </svg>
                    <span>Home</span>
                </a>
            </li>

            <li>
                <a href="explore.php" class="z-nav-link">
                    <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M10.5 3a7.5 7.5 0 0 1 5.96 12.06l4.24 4.24a1 1 0 0 1-1.4 1.4l-4.24-4.24A7.5 7.5 0 1 1 10.5 3zm0 2a5.5 5.5 0 1 0 0 11a5.5 5.5 0 0 0 0-11z"/>
                    </svg>
                    <span>Explore</span>
                </a>
            </li>

            <li>
                <a href="notifications.php" class="z-nav-link">
                    <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 22a2.75 2.75 0 0 0 2.68-2.13H9.32A2.75 2.75 0 0 0 12 22zm7-6.5l-1.6-2.1V9a5.41 5.41 0 0 0-4.3-5.3V2.75a1.1 1.1 0 0 0-2.2 0v.95A5.41 5.41 0 0 0 6.6 9v4.4L5 15.5a1.5 1.5 0 0 0 1.2 2.4h11.6a1.5 1.5 0 0 0 1.2-2.4z"/>
                    </svg>
                    <span>Notifications</span>
                </a>
            </li>

            <li>
                <a href="chat.php" class="z-nav-link">
                    <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 5a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H9.2l-4.07 3.39A1.3 1.3 0 0 1 3 18.39V5zm3-1a1 1 0 0 0-1 1v11.25L8.22 14.4A2 2 0 0 1 9.5 14H17a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H7z"/>
                    </svg>
                    <span>Chat</span>
                </a>
            </li>

            <li>
                <a href="profile.php" class="z-nav-link">
                    <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5zm0 2c-5.05 0-9 2.67-9 6.08A1.92 1.92 0 0 0 4.92 22h14.16A1.92 1.92 0 0 0 21 20.08C21 16.67 17.05 14 12 14z"/>
                    </svg>
                    <span>Profile</span>
                </a>
            </li>

            <?php if (isAdmin()): ?>
                <li>
                    <a href="admin.php" class="z-nav-link">
                        <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19.43 12.98c.04-.32.07-.65.07-.98s-.02-.66-.07-.98l2.11-1.65a.5.5 0 0 0 .12-.64l-2-3.46a.5.5 0 0 0-.6-.22l-2.49 1a7.28 7.28 0 0 0-1.69-.98L14.5 2.4A.5.5 0 0 0 14 2h-4a.5.5 0 0 0-.5.4L9.12 5.07c-.6.23-1.16.56-1.69.98l-2.49-1a.5.5 0 0 0-.6.22l-2 3.46a.5.5 0 0 0 .12.64l2.11 1.65c-.04.32-.07.65-.07.98s.02.66.07.98l-2.11 1.65a.5.5 0 0 0-.12.64l2 3.46a.5.5 0 0 0 .6.22l2.49-1c.53.41 1.09.74 1.69.98l.38 2.67a.5.5 0 0 0 .5.4h4a.5.5 0 0 0 .5-.4l.38-2.67c.6-.24 1.16-.57 1.69-.98l2.49 1a.5.5 0 0 0 .6-.22l2-3.46a.5.5 0 0 0-.12-.64l-2.11-1.65zM12 15.5A3.5 3.5 0 1 1 12 8a3.5 3.5 0 0 1 0 7.5z"/>
                        </svg>
                        <span>Admin</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="pb-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center overflow-hidden">
                <span class="font-bold"><?= e(substr($_SESSION['display_name'] ?? 'Z', 0, 1)) ?></span>
            </div>

            <div class="min-w-0">
                <p class="font-bold truncate"><?= e($_SESSION['display_name'] ?? 'User') ?></p>
                <p class="text-sm text-neutral-500 truncate">@<?= e($_SESSION['username'] ?? 'username') ?></p>
            </div>
        </div>

        <a href="logout.php" class="block text-center rounded-full border border-neutral-700 py-2 hover:bg-neutral-900 transition">
            Logout
        </a>
    </div>
</nav>

<nav class="lg:hidden fixed bottom-0 left-0 right-0 border-t border-neutral-800 bg-black z-50">
    <ul class="grid grid-cols-5 text-center">
        <li>
            <a href="index.php" class="z-mobile-nav-link">
                <svg class="z-mobile-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 10.75L12 3l9 7.75V21a1 1 0 0 1-1 1h-5.5a1 1 0 0 1-1-1v-5.5h-3V21a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.75z"/>
                </svg>
            </a>
        </li>

        <li>
            <a href="explore.php" class="z-mobile-nav-link">
                <svg class="z-mobile-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M10.5 3a7.5 7.5 0 0 1 5.96 12.06l4.24 4.24a1 1 0 0 1-1.4 1.4l-4.24-4.24A7.5 7.5 0 1 1 10.5 3zm0 2a5.5 5.5 0 1 0 0 11a5.5 5.5 0 0 0 0-11z"/>
                </svg>
            </a>
        </li>

        <li>
            <a href="notifications.php" class="z-mobile-nav-link">
                <svg class="z-mobile-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 22a2.75 2.75 0 0 0 2.68-2.13H9.32A2.75 2.75 0 0 0 12 22zm7-6.5l-1.6-2.1V9a5.41 5.41 0 0 0-4.3-5.3V2.75a1.1 1.1 0 0 0-2.2 0v.95A5.41 5.41 0 0 0 6.6 9v4.4L5 15.5a1.5 1.5 0 0 0 1.2 2.4h11.6a1.5 1.5 0 0 0 1.2-2.4z"/>
                </svg>
            </a>
        </li>

        <li>
            <a href="chat.php" class="z-mobile-nav-link">
                <svg class="z-mobile-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 5a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H9.2l-4.07 3.39A1.3 1.3 0 0 1 3 18.39V5zm3-1a1 1 0 0 0-1 1v11.25L8.22 14.4A2 2 0 0 1 9.5 14H17a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H7z"/>
                </svg>
            </a>
        </li>

        <li>
            <a href="profile.php" class="z-mobile-nav-link">
                <svg class="z-mobile-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5zm0 2c-5.05 0-9 2.67-9 6.08A1.92 1.92 0 0 0 4.92 22h14.16A1.92 1.92 0 0 0 21 20.08C21 16.67 17.05 14 12 14z"/>
                </svg>
            </a>
        </li>
    </ul>
</nav>