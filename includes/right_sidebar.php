<?php

require_once __DIR__ . '/../database/follow_queries.php';

$suggestedUsers = [];

if (isLoggedIn()) {
    $suggestedUsers = getSuggestedUsers($dbconn, (int) $_SESSION['user_id'], 2);
}

?>

<aside class="hidden xl:block fixed right-0 top-0 h-screen w-96 border-l border-neutral-800 bg-black px-6 py-5 overflow-y-auto">

    <section class="border border-neutral-800 rounded-2xl p-4 mb-4">
        <h2 class="font-bold text-lg mb-3">Who to follow</h2>

        <div class="space-y-4">
            <?php if (empty($suggestedUsers)): ?>
                <p class="text-sm text-neutral-500">No suggestions right now.</p>
            <?php endif; ?>

            <?php foreach ($suggestedUsers as $suggestedUser): ?>
                <div class="flex items-center justify-between gap-3">
                    <a href="profile.php?id=<?= (int) $suggestedUser['id'] ?>" class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-bold truncate">
                                <?= e($suggestedUser['display_name']) ?>
                            </p>

                            <?php if (($suggestedUser['role'] ?? '') === 'owner'): ?>
                                <span class="z-owner-rank">Owner</span>
                            <?php elseif (($suggestedUser['role'] ?? '') === 'admin'): ?>
                                <span class="z-admin-rank">Admin</span>
                            <?php endif; ?>
                        </div>

                        <p class="text-sm text-neutral-500 truncate">
                            @<?= e($suggestedUser['username']) ?>
                        </p>
                    </a>

                    <form action="follow_user.php" method="post">
                        <input type="hidden" name="user_id" value="<?= (int) $suggestedUser['id'] ?>">

                        <button
                            type="submit"
                            class="rounded-full bg-white text-black text-sm font-bold px-4 py-1.5 hover:opacity-90 transition"
                        >
                            Follow
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <canvas
        id="zClockCanvas"
        width="260"
        height="260"
        class="z-clock-canvas mb-4"

    ></canvas>
</aside>