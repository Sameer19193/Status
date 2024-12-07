<?php
include 'db.php';  // Include the database connection file

// CREATE: Insert new website with company name
function createWebsite($url, $companyName) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO websites (url, company_name) VALUES (:url, :company_name)");
    $stmt->execute([':url' => $url, ':company_name' => $companyName]);
    return $pdo->lastInsertId();  // Return the last inserted ID
}

// READ: Get all websites
function getWebsites() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM websites");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// UPDATE: Update a website's URL by ID
function updateWebsite($id, $newUrl) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE websites SET url = :url WHERE id = :id");
    $stmt->execute([':url' => $newUrl, ':id' => $id]);
    return $stmt->rowCount();  // Return the number of affected rows
}

// DELETE: Delete a website by ID
function deleteWebsite($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM websites WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount();  // Return the number of affected rows
}

// Check if the database and table exists, otherwise create the table
function createTableIfNotExists() {
    $pdo = getDBConnection();

    // Create the table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS websites (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        url TEXT NOT NULL
    )");

    // Check if the 'company_name' column exists
    $result = $pdo->query("PRAGMA table_info(websites)")->fetchAll(PDO::FETCH_ASSOC);
    $columnExists = false;

    // Look for the 'company_name' column in the table
    foreach ($result as $column) {
        if ($column['name'] === 'company_name') {
            $columnExists = true;
            break;
        }
    }

    // Add 'company_name' column if it doesn't exist
    if (!$columnExists) {
        $pdo->exec("ALTER TABLE websites ADD COLUMN company_name TEXT");
    }
}
?>
