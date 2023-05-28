<?php
ob_start();

$db_host = "eu-cdbr-west-03.cleardb.net";
$db_user = "bb8580a4c8f2f1";
$db_pass = "2a529bca";
$db_name = "heroku_0a1ecdf3cfac680";

try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
