<?php

require_once __DIR__ . '/db.php';

function isFollowing(PDO $dbconn, int $followerId, int $followingId): bool
{
    $sql = "
        SELECT id
        FROM follows
        WHERE follower_id = :follower_id
        AND following_id = :following_id
        LIMIT 1
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':follower_id' => $followerId,
        ':following_id' => $followingId
    ]);

    return (bool) $stmt->fetch();
}

function toggleFollow(PDO $dbconn, int $followerId, int $followingId): bool
{
    if ($followerId === $followingId) {
        return false;
    }

    if (isFollowing($dbconn, $followerId, $followingId)) {
        $sql = "
            DELETE FROM follows
            WHERE follower_id = :follower_id
            AND following_id = :following_id
        ";

        $stmt = $dbconn->prepare($sql);
        $stmt->execute([
            ':follower_id' => $followerId,
            ':following_id' => $followingId
        ]);

        return false;
    }

    $sql = "
        INSERT INTO follows (follower_id, following_id)
        VALUES (:follower_id, :following_id)
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':follower_id' => $followerId,
        ':following_id' => $followingId
    ]);

    return true;
}

function getSuggestedUsers(PDO $dbconn, int $currentUserId, int $limit = 2): array
{
    $limit = max(1, min($limit, 10));

    $sql = "
        SELECT
            users.id,
            users.username,
            users.display_name,
            users.role
        FROM users
        WHERE users.id != :current_user_id
        AND users.id NOT IN (
            SELECT following_id
            FROM follows
            WHERE follower_id = :current_user_id
        )
        ORDER BY users.id DESC
        LIMIT {$limit}
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':current_user_id' => $currentUserId
    ]);

    return $stmt->fetchAll();
}
function getFollowStats(PDO $dbconn, int $userId): array
{
    $sql = "
        SELECT
            (SELECT COUNT(*) FROM follows WHERE following_id = :user_id) AS follower_count,
            (SELECT COUNT(*) FROM follows WHERE follower_id = :user_id) AS following_count
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId
    ]);

    $stats = $stmt->fetch();

    return $stats ?: [
        'follower_count' => 0,
        'following_count' => 0
    ];
}
