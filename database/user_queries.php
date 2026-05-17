<?php
require_once __DIR__ . '/db.php';


function findUserByUsernameOrEmail(PDO $dbconn, string $login): ?array
{
    $sql = "SELECT * FROM users WHERE username = :login OR email = :login LIMIT 1";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':login' => $login
    ]);

    $user = $stmt->fetch();

    return $user ?: null;
}

function userExists(PDO $dbconn, string $username, string $email): bool
{
    $sql = "SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':email' => $email
    ]);

    return (bool) $stmt->fetch();
}

function createUser(PDO $dbconn, string $username, string $email, string $password, string $displayName): bool
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, display_name)
            VALUES (:username, :email, :password, :display_name)";

    $stmt = $dbconn->prepare($sql);

    return $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $passwordHash,
        ':display_name' => $displayName
    ]);
}

function getUserById(PDO $dbconn, int $id): ?array
{
    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':id' => $id
    ]);

    $user = $stmt->fetch();

    return $user ?: null;
}

function getUserStats(PDO $dbconn, int $userId): array
{
    $sql = "
        SELECT
            COUNT(DISTINCT posts.id) AS post_count,
            COUNT(DISTINCT likes.id) AS like_count
        FROM users
        LEFT JOIN posts ON users.id = posts.user_id
        LEFT JOIN likes ON users.id = likes.user_id
        WHERE users.id = :user_id
        GROUP BY users.id
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId
    ]);

    $stats = $stmt->fetch();

    return $stats ?: [
        'post_count' => 0,
        'like_count' => 0
    ];
}

function updateUserProfile(PDO $dbconn, int $userId, string $displayName, string $bio, ?string $profileImage, ?string $bannerImage): bool
{
    $sql = "
        UPDATE users
        SET display_name = :display_name,
            bio = :bio,
            profile_image = COALESCE(:profile_image, profile_image),
            banner_image = COALESCE(:banner_image, banner_image)
        WHERE id = :user_id
    ";

    $stmt = $dbconn->prepare($sql);

    return $stmt->execute([
        ':display_name' => $displayName,
        ':bio' => $bio,
        ':profile_image' => $profileImage,
        ':banner_image' => $bannerImage,
        ':user_id' => $userId
    ]);
}