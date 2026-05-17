<?php

$pageTitle = 'Profile - Z';
require_once __DIR__ . '/../database/follow_queries.php';
require_once __DIR__ . '/../database/user_queries.php';
require_once __DIR__ . '/../database/post_queries.php';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

function uploadProfileFile(array $file, string $folder): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > 3 * 1024 * 1024) {
        return null;
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $mimeType = mime_content_type($file['tmp_name']);

    if (!isset($allowedTypes[$mimeType])) {
        return null;
    }

    $extension = $allowedTypes[$mimeType];
    $fileName = uniqid('z_', true) . '.' . $extension;

    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadPath = $uploadDir . $fileName;
    $publicPath = 'uploads/' . $folder . '/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return null;
    }

    return $publicPath;
}

$loggedInUserId = (int) $_SESSION['user_id'];
$profileUserId = (int) ($_GET['id'] ?? $loggedInUserId);

$user = getUserById($dbconn, $profileUserId);

if (!$user) {
    redirect('index.php');
}

$isOwnProfile = $profileUserId === $loggedInUserId;
$stats = getUserStats($dbconn, $profileUserId);
$followStats = getFollowStats($dbconn, $profileUserId);
$userPosts = getPostsByUserId($dbconn, $profileUserId, $loggedInUserId);
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
        $profileImage = uploadProfileFile($_FILES['profile_image'] ?? [], 'profile');
        $bannerImage = uploadProfileFile($_FILES['banner_image'] ?? [], 'banners');

        if (updateUserProfile($dbconn, $loggedInUserId, $displayName, $bio, $profileImage, $bannerImage)) {
            $_SESSION['display_name'] = $displayName;
            $success = 'Profile updated.';
            $user = getUserById($dbconn, $loggedInUserId);
            $stats = getUserStats($dbconn, $loggedInUserId);
            $_SESSION['profile_image'] = $user['profile_image'];
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
           <?php if (!empty($user['banner_image'])): ?>
            <div class="h-36 bg-neutral-900 overflow-hidden">
                <img
                    src="<?= e($user['banner_image']) ?>"
                    alt="Profile banner"
                    class="w-full h-full object-cover"
                >
            </div>
        <?php else: ?>
            <div class="h-36 bg-neutral-900"></div>
        <?php endif; ?>

            <div class="px-5 pb-5">
                <div class="-mt-12 flex items-end justify-between">
                    <div class="w-24 h-24 rounded-full bg-neutral-800 border-4 border-black flex items-center justify-center overflow-hidden">
                        <?php if (!empty($user['profile_image'])): ?>
                            <img
                                src="<?= e($user['profile_image']) ?>"
                                alt="Profile picture"
                                class="w-full h-full object-cover"
                            >
                        <?php else: ?>
                            <span class="text-3xl font-bold">
                                <?= e(substr($user['display_name'] ?? 'Z', 0, 1)) ?>
                            </span>
                        <?php endif; ?>
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

            <form method="post" enctype="multipart/form-data" class="space-y-4">
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
                
                <div>
                    <label for="profile_image" class="block text-sm text-neutral-400 mb-2">Profile picture</label>
                    <input
                        id="profile_image"
                        name="profile_image"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-sm text-neutral-400 file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:font-bold file:text-black hover:file:opacity-90"
                    >
                </div>

                <div>
                    <label for="banner_image" class="block text-sm text-neutral-400 mb-2">Banner image</label>
                    <input
                        id="banner_image"
                        name="banner_image"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-sm text-neutral-400 file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:font-bold file:text-black hover:file:opacity-90"
                    >
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

         <section>
    <?php if (empty($userPosts)): ?>
        <div class="px-6 py-12 text-center text-neutral-500">
            <h2 class="text-xl font-bold text-white mb-2">No posts yet</h2>
            <p>This user has not posted anything yet.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($userPosts as $post): ?>
        <article class="border-b border-neutral-800 px-4 py-4 hover:bg-neutral-950 transition">
            <div class="flex gap-3">
                <a
                    href="profile.php?id=<?= (int) $post['user_id'] ?>"
                    class="w-11 h-11 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center shrink-0 hover:border-sky-500 transition overflow-hidden"
                >
                    <?php if (!empty($post['profile_image'])): ?>
                        <img
                            src="<?= e($post['profile_image']) ?>"
                            alt="Profile picture"
                            class="w-full h-full object-cover"
                        >
                    <?php else: ?>
                        <span class="font-bold"><?= e(substr($post['display_name'], 0, 1)) ?></span>
                    <?php endif; ?>
                </a>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <a
                                    href="profile.php?id=<?= (int) $post['user_id'] ?>"
                                    class="font-bold leading-tight hover:underline"
                                >
                                    <?= e($post['display_name']) ?>
                                </a>

                                <?php if (($post['role'] ?? '') === 'owner'): ?>
                                    <span class="z-owner-rank">Owner</span>
                                <?php elseif (($post['role'] ?? '') === 'admin'): ?>
                                    <span class="z-admin-rank">Admin</span>
                                <?php endif; ?>

                                <span class="text-neutral-500">·</span>
                                <span class="text-neutral-500 text-sm"><?= e(date('M j', strtotime($post['created_at']))) ?></span>
                            </div>

                            <a
                                href="profile.php?id=<?= (int) $post['user_id'] ?>"
                                class="block text-neutral-500 text-sm leading-tight"
                            >
                                @<?= e($post['username']) ?>
                            </a>
                        </div>
                    </div>

                    <a
                        href="post.php?id=<?= (int) $post['id'] ?>"
                        class="block mt-2 whitespace-pre-wrap break-words text-left hover:text-neutral-300 transition"
                    ><?= e($post['content']) ?></a>

                    <?php if (!empty($post['image_url'])): ?>
                        <a href="post.php?id=<?= (int) $post['id'] ?>">
                            <img
                                src="<?= e($post['image_url']) ?>"
                                alt="Post image"
                                class="mt-3 rounded-2xl border border-neutral-800 max-h-[500px] object-cover"
                            >
                        </a>
                    <?php endif; ?>

                    <div class="flex items-center gap-10 mt-4 text-neutral-500">
                        <a href="post.php?id=<?= (int) $post['id'] ?>" class="z-post-action hover:text-sky-500" title="Reply">
                            <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 5.5A3.5 3.5 0 0 1 7.5 2h9A3.5 3.5 0 0 1 20 5.5v7A3.5 3.5 0 0 1 16.5 16H9l-4.2 4.2A1 1 0 0 1 3 19.5v-14zm3.5-1.5A1.5 1.5 0 0 0 6 5.5v10.6l2.2-2.2A1 1 0 0 1 8.9 13h7.6A1.5 1.5 0 0 0 18 11.5v-6A1.5 1.5 0 0 0 16.5 4h-9z"/>
                            </svg>
                            <span><?= (int) $post['reply_count'] ?></span>
                        </a>

                        <form action="like_post.php" method="post" class="inline">
                            <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">

                            <button
                                class="z-post-action <?= ((int) $post['liked_by_current_user'] === 1) ? 'z-post-liked' : 'hover:text-red-500' ?>"
                                type="submit"
                                title="Like"
                            >
                                <?php if ((int) $post['liked_by_current_user'] === 1): ?>
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5A5.45 5.45 0 0 1 7.5 3A5.99 5.99 0 0 1 12 5.09A5.99 5.99 0 0 1 16.5 3A5.45 5.45 0 0 1 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="z-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5A5.45 5.45 0 0 1 7.5 3A5.99 5.99 0 0 1 12 5.09A5.99 5.99 0 0 1 16.5 3A5.45 5.45 0 0 1 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35zM7.5 5A3.45 3.45 0 0 0 4 8.5c0 2.86 2.85 5.44 7.9 10.03l.1.09l.1-.09C17.15 13.94 20 11.36 20 8.5A3.45 3.45 0 0 0 16.5 5c-1.74 0-3.41 1.02-4.22 2.6h-.56C10.91 6.02 9.24 5 7.5 5z"/>
                                    </svg>
                                <?php endif; ?>

                                <span><?= (int) $post['like_count'] ?></span>
                            </button>
                        </form>
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