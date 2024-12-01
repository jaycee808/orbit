<?php
ob_start();

$db_host = 
"sabaik6fx8he7pua.chr7pe7iynqr.eu-west-1.rds.amazonaws.com";
$db_user = "snk3kx62k2hq2nlk";
$db_pass = "i74awzbyulz06dwj";
$db_name = "mxdppjy54d224qzv";

try {
    $connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
