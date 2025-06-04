<?php
class Database {
    private const HOST = 'localhost';
    private const DB_NAME = 'file_management';
    private const USERNAME = 'root';
    private const PASSWORD = 'admin';

    public function connect() {
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s', self::HOST, self::DB_NAME);
            $conn = new PDO($dsn, self::USERNAME, self::PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
} 