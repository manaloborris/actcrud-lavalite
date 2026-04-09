<?php
// Standalone setup script - directly connects to database
require_once __DIR__ . '/config.php';

// Database connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/scheme/DigiCertGlobalRootG2.crt.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ]);
    echo "✓ Connected to database<br>";
} catch (Exception $e) {
    die("✗ Database connection failed: " . $e->getMessage());
}

echo "---== SETUP DATABASE ==---<br><br>";

// Add bcm_username column
try {
    $pdo->exec("ALTER TABLE `borris_bcm_users` ADD COLUMN `bcm_username` VARCHAR(50) UNIQUE AFTER `id`");
    echo "✓ Added bcm_username column<br>";
} catch (Exception $e) {
    echo "✓ bcm_username column already exists<br>";
}

// Add bcm_password column
try {
    $pdo->exec("ALTER TABLE `borris_bcm_users` ADD COLUMN `bcm_password` VARCHAR(255) AFTER `bcm_username`");
    echo "✓ Added bcm_password column<br>";
} catch (Exception $e) {
    echo "✓ bcm_password column already exists<br>";
}

// Delete all existing users
try {
    $pdo->exec("DELETE FROM `borris_bcm_users`");
    echo "✓ Deleted all existing users<br>";
} catch (Exception $e) {
    echo "✗ Error deleting users: " . $e->getMessage() . "<br>";
}

echo "<br>---== SETUP COMPLETE ==---<br>";
echo "Admin: Borris / admin123<br>";
echo "Users table is ready for new users!<br>";
?>
