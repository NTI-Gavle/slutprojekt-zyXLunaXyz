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

function createPostAndGetId(PDO $dbconn, int $userId, string $content): int
{
    $sql = "INSERT INTO posts (user_id, content)
            VALUES (:user_id, :content)";

    $stmt = $dbconn->prepare($sql);

    $stmt->execute([
        ':user_id' => $userId,
        ':content' => $content
    ]);

    return (int) $dbconn->lastInsertId();
}

function getAllPosts(PDO $dbconn, int $currentUserId): array
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
        ORDER BY posts.created_at DESC
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':current_user_id' => $currentUserId
    ]);

    return $stmt->fetchAll();
}

function deletePost(PDO $dbconn, int $postId, int $currentUserId, string $currentRole): bool
{
    {
        $sql = "
            SELECT 
                posts.user_id,
                users.role AS owner_role
            FROM posts
            INNER JOIN users ON posts.user_id = users.id
            WHERE posts.id = :post_id
            LIMIT 1
        ";

        $stmt = $dbconn->prepare($sql);
        $stmt->execute([
            ':post_id' => $postId
        ]);

        $post = $stmt->fetch();

        if (!$post) {
            return false;
        }

        $postOwnerId = (int) $post['user_id'];
        $postOwnerRole = $post['owner_role'];

        if ($postOwnerId === $currentUserId) {
            $sql = "DELETE FROM posts WHERE id = :post_id";
            $stmt = $dbconn->prepare($sql);

            $stmt->execute([
                ':post_id' => $postId
            ]);

            return $stmt->rowCount() > 0;
        }

        if ($currentRole === 'owner') {
            if ($postOwnerRole === 'owner') {
                return false;
            }

            $sql = "DELETE FROM posts WHERE id = :post_id";
            $stmt = $dbconn->prepare($sql);

            $stmt->execute([
                ':post_id' => $postId
            ]);

            return $stmt->rowCount() > 0;
        }

        if ($currentRole === 'admin') {
            if ($postOwnerRole !== 'user') {
                return false;
            }

            $sql = "DELETE FROM posts WHERE id = :post_id";
            $stmt = $dbconn->prepare($sql);

            $stmt->execute([
                ':post_id' => $postId
            ]);

            return $stmt->rowCount() > 0;
        }

        return false;
    }
}

function hasLikedPost(PDO $dbconn, int $postId, int $userId): bool
{
    $sql = "SELECT id 
            FROM likes 
            WHERE post_id = :post_id 
            AND user_id = :user_id 
            LIMIT 1";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':post_id' => $postId,
        ':user_id' => $userId
    ]);

    return (bool) $stmt->fetch();
}

function getLikeCount(PDO $dbconn, int $postId): int
{
    $sql = "
        SELECT COUNT(*) AS like_count
        FROM likes
        WHERE post_id = :post_id
    ";

    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':post_id' => $postId
    ]);

    $result = $stmt->fetch();

    return (int) ($result['like_count'] ?? 0);
}

function toggleLike(PDO $dbconn, int $postId, int $userId): bool
{
    if (hasLikedPost($dbconn, $postId, $userId)) {
        $sql = "DELETE FROM likes 
                WHERE post_id = :post_id 
                AND user_id = :user_id";

        $stmt = $dbconn->prepare($sql);

        return $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId
        ]);
    }

    $sql = "INSERT INTO likes (post_id, user_id)
            VALUES (:post_id, :user_id)";

    $stmt = $dbconn->prepare($sql);

    return $stmt->execute([
        ':post_id' => $postId,
        ':user_id' => $userId
    ]);
}