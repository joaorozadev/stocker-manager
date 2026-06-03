<?php
// Arquivo: config/database.php

function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

function getConexao() {
    loadEnv(__DIR__ . '/../.env');

    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? 'stocker_manager'; 
    $user = $_ENV['DB_USER'] ?? 'postgres';
    $pass = $_ENV['DB_PASS'] ?? '';
    $port = $_ENV['DB_PORT'] ?? '5432';

    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados. " . $e->getMessage());
    }
}