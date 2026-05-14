<?php

require_once __DIR__ . '/db.php';

function createPost(PDO $dbconn, int $userId, string $content): bool
{
    $sql = "INSERT INTO posts (user_id, content)
            VALUES (:user_id, :content)";

    $stmt = $dbconn->prepare($sql);

    return $stmt->execute([
        ':user_id' => $userId,
        ':content' => $content
    ]);
}

function getAllPosts(PDO $dbconn): array
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
            COUNT(DISTINCT likes.id) AS like_count,
            COUNT(DISTINCT replies.id) AS reply_count
        FROM posts
        INNER JOIN users ON posts.user_id = users.id
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN replies ON posts.id = replies.post_id
        GROUP BY posts.id
        ORDER BY posts.created_at DESC
    ";

    $stmt = $dbconn->query($sql);

    return $stmt->fetchAll();
}