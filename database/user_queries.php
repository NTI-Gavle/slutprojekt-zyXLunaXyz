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