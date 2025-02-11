<?php
  function loadEnv($filePath): array {
    if (!file_exists($filePath)) {
        die("Environment file not found.");
    }
    return parse_ini_file($filePath);
    }

    function getDatabaseConnection(): PDO {
        $env = loadEnv(__DIR__ . '/config.env');

        // Use null coalescing operator to set default values if env variables are not present
        $dsn = sprintf(
            "mysql:host=%s;port=%s;dbname=%s;charset=%s",
            $env['DB_HOST'] ?? 'localhost',
            $env['DB_PORT'] ?? '3306',
            $env['DB_NAME'] ?? '',
            $env['DB_CHARSET'] ?? '',
            $env['DB_PASS'] ?? '',
            $env['DB_USER'] ?? ''
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . ($env['DB_CHARSET'] ?? 'utf8mb4') . 
                                            " COLLATE " . ($env['DB_COLLATION'] ?? 'utf8mb4_unicode_ci'),
            PDO::ATTR_TIMEOUT            => (int)($env['DB_CONNECTION_TIMEOUT'] ?? 10),
        ];

        return new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], $options);
    }    
    
    $pdo = getDatabaseConnection();
    
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }



