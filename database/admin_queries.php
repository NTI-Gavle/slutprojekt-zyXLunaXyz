<?php

require_once __DIR__ . '/db.php';

function getUserRoleById(PDO $dbconn, int $userId): ?string
{
    $sql = "SELECT role FROM users WHERE id = :user_id LIMIT 1";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId
    ]);

    $user = $stmt->fetch();

    return $user ? $user['role'] : null;
}

function deleteUserById(PDO $dbconn, int $userId): bool
{
    $sql = "DELETE FROM users WHERE id = :user_id";
    $stmt = $dbconn->prepare($sql);

    $stmt->execute([
        ':user_id' => $userId
    ]);

    return $stmt->rowCount() > 0;
}
