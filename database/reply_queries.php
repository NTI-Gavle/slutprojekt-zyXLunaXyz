<?php

require_once __DIR__ . '/db.php';

function getPostById(PDO $dbconn, int $postId, int $currentUserId): ?array
{
    $sql = "
        SELECT
            posts.id,
            posts.user_id,
            posts.content,
            posts.image_url,
            posts.created_at,
            users.username,
            users.display_name,
            users.profile_image,
            users.role,
            COUNT(DISTINCT likes.id) AS like_count,
            COUNT(DISTINCT replies.id) AS reply_count,
            MAX(CASE WHEN user_likes.user_id IS NOT NULL THEN 1 ELSE 0 END) AS liked_by_current_user
        FROM posts
        INNER JOIN users ON posts.user_id = users.id
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN replies ON posts.id = replies.post_id
        LEFT JOIN likes AS user_likes
            ON posts.id = user_likes.post_id
            AND user_likes.user_id = :current_user_id
        WHERE posts.id = :post_id
        GROUP BY
            posts.id,
            posts.user_id,
            posts.content,
            posts.image_url,
            posts.created_at,
            users.username,
            users.display_name,
            users.profile_image,
            users.role
        LIMIT 1
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':post_id' => $postId,
        ':current_user_id' => $currentUserId
    ]);

    $post = $stmt->fetch();

    return $post ?: null;
}

function createReply(PDO $dbconn, int $postId, int $userId, string $content): bool
{
    $sql = "
        INSERT INTO replies (post_id, user_id, content)
        VALUES (:post_id, :user_id, :content)
    ";

    $stmt = $dbconn->prepare($sql);

    return $stmt->execute([
        ':post_id' => $postId,
        ':user_id' => $userId,
        ':content' => $content
    ]);
}

function getRepliesByPostId(PDO $dbconn, int $postId): array
{
    $sql = "
        SELECT
            replies.id,
            replies.post_id,
            replies.user_id,
            replies.content,
            replies.created_at,
            users.username,
            users.display_name,
            users.profile_image,
            users.role
        FROM replies
        INNER JOIN users ON replies.user_id = users.id
        WHERE replies.post_id = :post_id
        ORDER BY replies.created_at ASC
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':post_id' => $postId
    ]);

    return $stmt->fetchAll();
}