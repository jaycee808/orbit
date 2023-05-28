<?php
ob_start();

$db_host = "eu-cdbr-west-03.cleardb.net";
$db_user = "b2ccc9f753b5ac";
$db_pass = "889c3d10";
$db_name = "heroku_f00d3f311846201";

try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
