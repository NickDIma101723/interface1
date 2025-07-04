<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_file = 'products.sqlite';
try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e){
    echo $e->getMessage();
}

?>
