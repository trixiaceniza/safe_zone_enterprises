<?php
// Adjust credentials here only.
const DB_HOST='localhost', DB_NAME='safe_zone_enterprises', DB_USER='root', DB_PASS='';
try {
  $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS,
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
} catch (PDOException $e) { die('Database connection failed. Check config/database.php and that the database was imported.'); }
