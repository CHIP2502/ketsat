<?php
declare(strict_types=1);

function db(array $config): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseDir = dirname($config['database_path']);
    if (!is_dir($databaseDir)) {
        mkdir($databaseDir, 0755, true);
    }

    $pdo = new PDO('sqlite:' . $config['database_path']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    migrate($pdo);

    return $pdo;
}

function migrate(PDO $pdo): void
{
    $pdo->exec(<<<'SQL'
        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            short_description TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL DEFAULT '',
            price TEXT NOT NULL DEFAULT 'Liên hệ',
            category TEXT NOT NULL DEFAULT '',
            featured INTEGER NOT NULL DEFAULT 0,
            is_active INTEGER NOT NULL DEFAULT 1,
            image_path TEXT NOT NULL DEFAULT '',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
    SQL);
}
