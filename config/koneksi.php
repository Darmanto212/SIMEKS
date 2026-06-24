<?php
/**
 * Database Connection Configuration
 * SIMEKS - SMAN 2 Sukatani
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "simeks_db";

try {
    // Create a PDO connection
    $koneksi = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Set PDO error mode to exception
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to object
    $koneksi->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    
} catch(PDOException $e) {
    // Connection failed
    // In production, log the error instead of showing it
    die("Koneksi gagal: " . $e->getMessage());
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
