<?php
$host = "127.0.0.1"; 
$db   = "kolesnm_db";
$user = "kolesnm_local";
$pass = "2oHN7n5-3S93jd_1";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
     $dbh = new PDO($dsn, $user, $pass);
     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     die("DATABASE CONNECTION FAILED: " . $e->getMessage());
}
?>