<?php

$pageTitle = 'Home - Z';

require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$posts = getAllPosts($dbconn);

?>

<div class="min-h-screen bg-black text-white">
    <?php require_once __DIR__ . '/../includes/nav.php'; ?>

    <main class="min-h-screen lg:ml-72 xl:mr-96 border-x border-neutral-800">

        <section class="sticky top-0 z-20 bg-black/80 backdrop-blur border-b border-neutral-800">
            <div class="grid grid-cols-2 text-center font-bold">
                <button class="py-4 border-b-4 border-sky-500">
                    For you
                </button>

                <button class="py-4 text-neutral-400 hover:bg-neutral-900 transition">
                    Following
                </button>
            </div>
        </section>

        <section class="border-b border-neutral-800 px-4 py-4">
            <form action="create_post.php" method="post">
                <div class="flex gap-3">
                    <div class="w-11 h-11 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center shrink-0">
                        <span class="font-bold"><?= e(substr($_SESSION['display_name'] ?? 'Z', 0, 1)) ?></span>
                    </div>

                    <div class="flex-1">
                        <textarea
                            name="content"
                            rows="3"
                            maxlength="280"
                            placeholder="What’s happening?"
                            class="w-full resize-none bg-black text-white text-xl outline-none placeholder:text-neutral-500"
                            required
                        ></textarea>

                        <div class="flex items-center justify-between border-t border-neutral-900 pt-3">
                            <div class="flex gap-4 text-sky-500">
                                <button type="button" class="z-icon-button" title="Image">
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M5 4h14a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3zm0 2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2.2l-4.2-4.2a1 1 0 0 0-1.4 0L10 15l-1.7-1.7a1 1 0 0 0-1.4 0L4 16.2V17a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5zm3.5 2.5A1.5 1.5 0 1 1 7 10a1.5 1.5 0 0 1 1.5-1.5z"/>
                                    </svg>
                                </button>

                                <button type="button" class="z-icon-button" title="GIF">
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm0 2v10h16V7H4zm3 3h3v1.5H8.5v1H10V14H8.5v1H7v-5zm4.5 0H13v5h-1.5v-5zm3 0H18v1.5h-2v1h1.7V14H16v1h-1.5v-5z"/>
                                    </svg>
                                </button>
                            </div>

                            <button
                                type="submit"
                                class="rounded-full bg-white text-black px-5 py-1.5 font-bold hover:opacity-90 transition"
                            >
                                Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <section>
            <?php if (empty($posts)): ?>
                <div class="px-6 py-16 text-center text-neutral-500">
                    <h2 class="text-2xl font-bold text-white mb-2">No posts yet</h2>
                    <p>Be the first one to post on Z.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($posts as $post): ?>
                <article class="border-b border-neutral-800 px-4 py-4 hover:bg-neutral-950 transition">
                    <div class="flex gap-3">
                        <div class="w-11 h-11 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center shrink-0">
                            <span class="font-bold"><?= e(substr($post['display_name'], 0, 1)) ?></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <span class="font-bold"><?= e($post['display_name']) ?></span>
                                    <span class="text-neutral-500 text-sm">@<?= e($post['username']) ?></span>
                                    <span class="text-neutral-500">·</span>
                                    <span class="text-neutral-500 text-sm"><?= e(date('M j', strtotime($post['created_at']))) ?></span>
                                </div>

                                <button class="z-post-menu-button" type="button" title="More">
                                    <svg class="z-action-icon-small" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M5 12a2 2 0 1 1-2-2a2 2 0 0 1 2 2zm9 0a2 2 0 1 1-2-2a2 2 0 0 1 2 2zm9 0a2 2 0 1 1-2-2a2 2 0 0 1 2 2z"/>
                                    </svg>
                                </button>
                            </div>

                            <p class="mt-2 whitespace-pre-wrap break-words">
                                <?= e($post['content']) ?>
                            </p>

                            <?php if (!empty($post['image_url'])): ?>
                                <img
                                    src="<?= e($post['image_url']) ?>"
                                    alt="Post image"
                                    class="mt-3 rounded-2xl border border-neutral-800 max-h-[500px] object-cover"
                                >
                            <?php endif; ?>

                            <div class="flex items-center justify-between max-w-md mt-4 text-neutral-500">
                                <button class="z-post-action hover:text-sky-500" type="button" title="Reply">
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 5.5A3.5 3.5 0 0 1 7.5 2h9A3.5 3.5 0 0 1 20 5.5v7A3.5 3.5 0 0 1 16.5 16H9l-4.2 4.2A1 1 0 0 1 3 19.5v-14zm3.5-1.5A1.5 1.5 0 0 0 6 5.5v10.6l2.2-2.2A1 1 0 0 1 8.9 13h7.6A1.5 1.5 0 0 0 18 11.5v-6A1.5 1.5 0 0 0 16.5 4h-9z"/>
                                    </svg>
                                    <span><?= (int) $post['reply_count'] ?></span>
                                </button>

                                <button class="z-post-action hover:text-green-500" type="button" title="Repost">
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M7 7h9.2l-2.1-2.1a1 1 0 0 1 1.4-1.4l3.8 3.8a1 1 0 0 1 0 1.4l-3.8 3.8a1 1 0 0 1-1.4-1.4L16.2 9H7a2 2 0 0 0-2 2v1a1 1 0 1 1-2 0v-1a4 4 0 0 1 4-4zm10 10H7.8l2.1 2.1a1 1 0 0 1-1.4 1.4l-3.8-3.8a1 1 0 0 1 0-1.4l3.8-3.8a1 1 0 0 1 1.4 1.4L7.8 15H17a2 2 0 0 0 2-2v-1a1 1 0 1 1 2 0v1a4 4 0 0 1-4 4z"/>
                                    </svg>
                                    <span>0</span>
                                </button>

                                <button class="z-post-action hover:text-red-500" type="button" title="Like">
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5A5.45 5.45 0 0 1 7.5 3A5.99 5.99 0 0 1 12 5.09A5.99 5.99 0 0 1 16.5 3A5.45 5.45 0 0 1 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35zM7.5 5A3.45 3.45 0 0 0 4 8.5c0 2.86 2.85 5.44 7.9 10.03l.1.09l.1-.09C17.15 13.94 20 11.36 20 8.5A3.45 3.45 0 0 0 16.5 5c-1.74 0-3.41 1.02-4.22 2.6h-.56C10.91 6.02 9.24 5 7.5 5z"/>
                                    </svg>
                                    <span><?= (int) $post['like_count'] ?></span>
                                </button>

                                <button class="z-post-action hover:text-sky-500" type="button" title="Share">
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M18 16.1c-.76 0-1.44.3-1.95.77L8.91 12.7a3.27 3.27 0 0 0 0-1.39l7.05-4.12A3 3 0 1 0 15 5a3.08 3.08 0 0 0 .05.53L8 9.65a3 3 0 1 0 0 4.7l7.12 4.18A2.77 2.77 0 0 0 15 19a3 3 0 1 0 3-2.9z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <?php require_once __DIR__ . '/../includes/right_sidebar.php'; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>