<?php

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dbPath = __DIR__ . '/storage/database.sqlite';
            self::$instance = new PDO("sqlite:$dbPath");
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::init();
        }
        return self::$instance;
    }

    private static function init(): void
    {
        self::$instance->exec("
            CREATE TABLE IF NOT EXISTS cvs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                contenido_json TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public static function getAll(): array
    {
        $stmt = self::getInstance()->query("SELECT id, nombre, created_at FROM cvs ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $stmt = self::getInstance()->prepare("SELECT * FROM cvs WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            $row['contenido'] = json_decode($row['contenido_json'], true);
        }
        return $row ?: null;
    }

    public static function save(string $nombre, array $contenido): int
    {
        $db = self::getInstance();
        $stmt = $db->prepare("INSERT INTO cvs (nombre, contenido_json) VALUES (?, ?)");
        $stmt->execute([$nombre, json_encode($contenido)]);
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, string $nombre, array $contenido): void
    {
        $stmt = self::getInstance()->prepare("UPDATE cvs SET nombre = ?, contenido_json = ? WHERE id = ?");
        $stmt->execute([$nombre, json_encode($contenido), $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = self::getInstance()->prepare("DELETE FROM cvs WHERE id = ?");
        $stmt->execute([$id]);
    }
}
