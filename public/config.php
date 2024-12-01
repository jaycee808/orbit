<?php
ob_start();

$host = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$dbname = getenv('MYSQLDATABASE') ?: 'railway';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: 'AUCJPTPtNrSvaBaHlJERimcxKKDFcjrr';

try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
