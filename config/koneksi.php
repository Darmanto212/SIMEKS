<?php
/**
 * Database Connection Configuration
 * SIMEKS - SMAN 2 Sukatani
 */

// Simple helper function to load .env variables
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip comments and empty lines
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                // Remove quotes if present
                $value = trim($value, '"\'');
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
}

// Load env configuration from root directory
loadEnv(__DIR__ . '/../.env');

// Retrieve DB credentials from environment variables with safe fallbacks
$host    = getenv('DB_HOST') ?: 'localhost';
$port    = getenv('DB_PORT') ?: '3306';
$db      = getenv('DB_NAME') ?: 'simeks_db';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

try {
    // Create a PDO connection with charset specified
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $koneksi = new PDO($dsn, $user, $pass);
    
    // Set PDO error mode to exception
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to object
    $koneksi->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    
} catch (PDOException $e) {
    // Log the actual error internally in server log (without leaking user or database credentials to user)
    error_log("Database connection error: " . $e->getMessage());
    
    // Show a user-friendly generic error page without leaking path information
    http_response_code(500);
    die("Koneksi database gagal. Silakan hubungi administrator.");
}


/**
 * Useful global functions
 */

function redirect($url) {
    header("Location: $url");
    exit();
}

function flash($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}
?>
