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

            <li>
                <a href="profile.php" class="z-nav-link">
                    <svg class="z-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 12a5 5 0 1 0-5-5a5 5 0 0 0 5 5zm0 2c-5.05 0-9 2.67-9 6.08A1.92 1.92 0 0 0 4.92 22h14.16A1.92 1.92 0 0 0 21 20.08C21 16.67 17.05 14 12 14z"/>
                    </svg>
                    <span>Profile</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="pb-4">
        <a href="profile.php">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center overflow-hidden">
                <?php if (!empty($_SESSION['profile_image'])): ?>
                    <img
                        src="<?= e($_SESSION['profile_image']) ?>"
                        alt="Profile picture"
                        class="w-full h-full object-cover"
                    >
                <?php else: ?>
                    <span class="font-bold"><?= e(substr($_SESSION['display_name'] ?? 'Z', 0, 1)) ?></span>
                <?php endif; ?>
            </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-bold truncate leading-tight">
                                <?= e($_SESSION['display_name'] ?? 'User') ?>
                            </p>

                            <p class="text-sm text-neutral-500 truncate leading-tight">
                                @<?= e($_SESSION['username'] ?? 'username') ?>
                            </p>
                        </div>

                        <?php if (isOwner()): ?>
                            <span class="z-owner-rank z-admin-rank-nav">Owner</span>
                        <?php elseif (($_SESSION['role'] ?? '') === 'admin'): ?>
                            <span class="z-admin-rank z-admin-rank-nav">Admin</span>
                        <?php endif; ?>
                    </div>
                </div>
        </div>
        </a>
        <a href="logout.php" class="block text-center rounded-full border border-neutral-700 py-2 hover:bg-neutral-900 transition">
            Logout
        </a>
    </div>
</nav>

<nav class="lg:hidden fixed bottom-0 left-0 right-0 border-t border-neutral-800 bg-black z-50">
    <ul class="grid grid-cols-2 text-center">
        <li>
            <a href="index.php" class="z-mobile-nav-link">
                <svg class="z-mobile-nav-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 10.75L12 3l9 7.75V21a1 1 0 0 1-1 1h-5.5a1 1 0 0 1-1-1v-5.5h-3V21a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.75z"/>
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