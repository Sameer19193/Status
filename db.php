<?php
// Database connection function
function getDBConnection() {
    $dbFile = 'websites.db';  // SQLite database file
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}
?>
