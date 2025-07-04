<?php
// Include the config file for the database connection
require_once 'config.php';

try {
    // Create products table if it doesn't exist
    $query = "CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        naam TEXT NOT NULL,
        omschrijving TEXT,
        maat TEXT CHECK(maat IN ('XS', 'S', 'M', 'L', 'XL') OR maat IS NULL),
        afbeelding TEXT,
        prijs INTEGER
    )";
    
    $conn->exec($query);
    // The connection is already available as $conn from config.php
} catch(PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    die();
}
?>
