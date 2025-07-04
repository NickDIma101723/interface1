<?php
// Include database connection
require 'config.php';

try {
    // Make sure the table exists
    require_once 'database.php';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    // Sample product 1: T-shirt
    $naam1 = 'Premium Vintage T-shirt';
    $omschrijving1 = 'Hoogwaardige vintage T-shirt gemaakt van 100% biologisch katoen. Limited edition streetwear.';
    $maat1 = 'M';
    $afbeelding1 = 'sample_tshirt.jpg';
    $prijs1 = 2995;
    
    // Sample product 2: Hoodie
    $naam2 = 'Urban Hoodie Zwart';
    $omschrijving2 = 'Comfortabele zwarte hoodie met zakken, perfect voor stedelijke stijl. Duurzaam geproduceerd.';
    $maat2 = 'L';
    $afbeelding2 = 'sample_hoodie.jpg';
    $prijs2 = 5990;
    
    // Sample product 3: Sneakers
    $naam3 = 'Exclusive Sneakers';
    $omschrijving3 = 'Limited edition sneakers met uniek design. Handgemaakt met duurzame materialen.';
    $maat3 = 'XL';
    $afbeelding3 = 'sample_sneakers.jpg';
    $prijs3 = 12950;
    
    // Create sample image 1
    $imagePath1 = 'uploads/' . $afbeelding1;
    if (!file_exists($imagePath1)) {
        $img1 = imagecreatetruecolor(800, 1000);
        $color1 = imagecolorallocate($img1, 200, 200, 240); // Light blue
        imagefill($img1, 0, 0, $color1);
        imagejpeg($img1, $imagePath1);
        imagedestroy($img1);
    }
    
    // Create sample image 2
    $imagePath2 = 'uploads/' . $afbeelding2;
    if (!file_exists($imagePath2)) {
        $img2 = imagecreatetruecolor(800, 1000);
        $color2 = imagecolorallocate($img2, 50, 50, 50); // Dark gray
        imagefill($img2, 0, 0, $color2);
        imagejpeg($img2, $imagePath2);
        imagedestroy($img2);
    }
    
    // Create sample image 3
    $imagePath3 = 'uploads/' . $afbeelding3;
    if (!file_exists($imagePath3)) {
        $img3 = imagecreatetruecolor(800, 1000);
        $color3 = imagecolorallocate($img3, 240, 200, 200); // Light red
        imagefill($img3, 0, 0, $color3);
        imagejpeg($img3, $imagePath3);
        imagedestroy($img3);
    }
    
    // Insert product 1 if it doesn't exist
    $insertCount = 0;
    
    $checkQuery = "SELECT COUNT(*) FROM products WHERE naam = :naam";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':naam', $naam1);
    $checkStmt->execute();
    
    if ($checkStmt->fetchColumn() == 0) {
        $query = "INSERT INTO products (naam, omschrijving, maat, afbeelding, prijs) VALUES (:naam, :omschrijving, :maat, :afbeelding, :prijs)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':naam', $naam1);
        $stmt->bindParam(':omschrijving', $omschrijving1);
        $stmt->bindParam(':maat', $maat1);
        $stmt->bindParam(':afbeelding', $afbeelding1);
        $stmt->bindParam(':prijs', $prijs1);
        $stmt->execute();
        $insertCount++;
    }
    
    // Insert product 2 if it doesn't exist
    $checkQuery = "SELECT COUNT(*) FROM products WHERE naam = :naam";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':naam', $naam2);
    $checkStmt->execute();
    
    if ($checkStmt->fetchColumn() == 0) {
        $query = "INSERT INTO products (naam, omschrijving, maat, afbeelding, prijs) VALUES (:naam, :omschrijving, :maat, :afbeelding, :prijs)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':naam', $naam2);
        $stmt->bindParam(':omschrijving', $omschrijving2);
        $stmt->bindParam(':maat', $maat2);
        $stmt->bindParam(':afbeelding', $afbeelding2);
        $stmt->bindParam(':prijs', $prijs2);
        $stmt->execute();
        $insertCount++;
    }
    
    // Insert product 3 if it doesn't exist
    $checkQuery = "SELECT COUNT(*) FROM products WHERE naam = :naam";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':naam', $naam3);
    $checkStmt->execute();
    
    if ($checkStmt->fetchColumn() == 0) {
        $query = "INSERT INTO products (naam, omschrijving, maat, afbeelding, prijs) VALUES (:naam, :omschrijving, :maat, :afbeelding, :prijs)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':naam', $naam3);
        $stmt->bindParam(':omschrijving', $omschrijving3);
        $stmt->bindParam(':maat', $maat3);
        $stmt->bindParam(':afbeelding', $afbeelding3);
        $stmt->bindParam(':prijs', $prijs3);
        $stmt->execute();
        $insertCount++;
    }
    
    echo "<p>Added $insertCount sample products to the database.</p>";
    echo "<p>You can now <a href='products.php'>view all products</a> or <a href='index.php'>return to the homepage</a>.</p>";
    
} catch(PDOException $e) {
    echo "<p>Error occurred!</p>";
    echo "<p>Query: " . $query . "</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    exit;
}
?>
