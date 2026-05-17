<?php

$pageTitle = 'Profile - Z';
require_once __DIR__ . '/../database/follow_queries.php';
require_once __DIR__ . '/../database/user_queries.php';
require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$loggedInUserId = (int) $_SESSION['user_id'];
$profileUserId = (int) ($_GET['id'] ?? $loggedInUserId);

$user = getUserById($dbconn, $profileUserId);

if (!$user) {
    redirect('index.php');
}

$isOwnProfile = $profileUserId === $loggedInUserId;
$stats = getUserStats($dbconn, $profileUserId);
$followStats = getFollowStats($dbconn, $profileUserId);
$isFollowingProfile = false;

if (!$isOwnProfile) {
    $isFollowingProfile = isFollowing($dbconn, $loggedInUserId, $profileUserId);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isOwnProfile) {
    $displayName = trim($_POST['display_name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    if ($displayName === '') {
        $error = 'Display name cannot be empty.';
    } elseif (mb_strlen($displayName) > 50) {
        $error = 'Display name cannot be longer than 50 characters.';
    } elseif (mb_strlen($bio) > 160) {
        $error = 'Bio cannot be longer than 160 characters.';
    } else {
        if (updateUserProfile($dbconn, $loggedInUserId, $displayName, $bio)) {
            $_SESSION['display_name'] = $displayName;
            $success = 'Profile updated.';
            $user = getUserById($dbconn, $loggedInUserId);
            $stats = getUserStats($dbconn,$loggedInUserId);
        } else {
            $error = 'Something went wrong.';
        }
    }
}

?>

<div class="min-h-screen bg-black text-white">
    <?php require_once __DIR__ . '/../includes/nav.php'; ?>

    <main class="min-h-screen lg:ml-72 xl:mr-96 border-x border-neutral-800">
        <section class="sticky top-0 z-20 bg-black/80 backdrop-blur border-b border-neutral-800 px-5 py-4">
            <h1 class="text-xl font-bold">Profile</h1>
            <p class="text-sm text-neutral-500">@<?= e($user['username'] ?? '') ?></p>
        </section>

        <section class="border-b border-neutral-800">
            <div class="h-36 bg-neutral-900"></div>

            <div class="px-5 pb-5">
                <div class="-mt-12 flex items-end justify-between">
                    <div class="w-24 h-24 rounded-full bg-neutral-800 border-4 border-black flex items-center justify-center overflow-hidden">
                        <span class="text-3xl font-bold">
                            <?= e(substr($user['display_name'] ?? 'Z', 0, 1)) ?>
                        </span>
                    </div>
                        <?php if ($isOwnProfile): ?>
                            <button
                                type="button"
                                data-auth-toggle="editProfileForm"
                                class="rounded-full border border-neutral-700 px-5 py-2 font-bold hover:bg-neutral-900 transition"
                            >
                                Edit profile
                            </button>
                        <?php else: ?>
                            <form action="follow_user.php" method="post">
                                <input type="hidden" name="user_id" value="<?= (int) $profileUserId ?>">
                                <input type="hidden" name="redirect_to" value="profile.php?id=<?= (int) $profileUserId ?>">

                                <button
                                    type="submit"
                                    class="<?= $isFollowingProfile ? 'rounded-full border border-neutral-700 px-5 py-2 font-bold hover:bg-red-500/10 hover:text-red-400 transition' : 'rounded-full bg-white text-black px-5 py-2 font-bold hover:opacity-90 transition' ?>"
                                >
                                    <?= $isFollowingProfile ? 'Following' : 'Follow' ?>
                                </button>
                            </form>
                        <?php endif; ?>


                </div>

                <div class="mt-4">
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-bold">
                            <?= e($user['display_name'] ?? 'User') ?>
                        </h2>

                        <?php if (($user['role'] ?? '') === 'owner'): ?>
                            <span class="z-owner-rank">Owner</span>
                        <?php elseif (($user['role'] ?? '') === 'admin'): ?>
                            <span class="z-admin-rank">Admin</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-neutral-500">@<?= e($user['username'] ?? '') ?></p>

                    <?php if (!empty($user['bio'])): ?>
                        <p class="mt-4 whitespace-pre-wrap break-words"><?= e($user['bio']) ?></p>
                    <?php else: ?>
                        <p class="mt-4 text-neutral-500">No bio yet.</p>
                    <?php endif; ?>

                    <div class="mt-4 flex gap-6 text-sm text-neutral-500">
                        <p>
                            <span class="font-bold text-white"><?= (int) $stats['post_count'] ?></span>
                            posts
                        </p>

                        <p>
                            <span class="font-bold text-white"><?= (int) $stats['like_count'] ?></span>
                            likes given
                        </p>

                        <p>
                            <span class="font-bold text-white"><?= (int) $followStats['follower_count'] ?></span>
                            followers
                        </p>

                        <p>
                            <span class="font-bold text-white"><?= (int) $followStats['following_count'] ?></span>
                            following
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <?php if ($isOwnProfile):?>
        <section id="editProfileForm" class="hidden border-b border-neutral-800 px-5 py-5">
            <h2 class="text-xl font-bold mb-4">Edit profile</h2>

            <?php if ($error): ?>
                <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-4 rounded-xl border border-green-500/40 bg-green-500/10 px-4 py-3 text-green-300">
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-4">
                <div>
                    <label for="display_name" class="block text-sm text-neutral-400 mb-2">Display name</label>
                    <input
                        id="display_name"
                        name="display_name"
                        type="text"
                        maxlength="50"
                        value="<?= e($user['display_name'] ?? '') ?>"
                        class="z-input"
                        required
                    >
                </div>

                <div>
                    <label for="bio" class="block text-sm text-neutral-400 mb-2">Bio</label>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        maxlength="160"
                        class="w-full rounded-2xl border border-neutral-700 bg-black px-4 py-3 outline-none focus:border-sky-500 resize-none"
                        placeholder="Tell people about yourself"
                    ><?= e($user['bio'] ?? '') ?></textarea>
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-white text-black px-5 py-2 font-bold hover:opacity-90 transition"
                >
                    Save profile
                </button>
            </form>
        </section>
         <?php endif; ?>
    </main>

    <?php require_once __DIR__ . '/../includes/right_sidebar.php'; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>