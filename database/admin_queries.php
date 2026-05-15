<?php

require_once __DIR__ . '/db.php';

function deleteUserById(PDO $dbconn, int $userId): bool
{
    $sql = "DELETE FROM users WHERE id = :user_id";
    $stmt = $dbconn->prepare($sql);

    $stmt->execute([
        ':user_id' => $userId
    ]);

    return $stmt->rowCount() > 0;
}