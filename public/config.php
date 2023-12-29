config.php file for old orbit

<?php
ob_start();

$db_host = "eu-cluster-west-01.k8s.cleardb.net";
$db_user = "b0a8b12ba113bc";
$db_pass = "802fce8a";
$db_name = "heroku_9f87f24fd978d58";

try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
