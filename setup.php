<?php
// One-time setup. Creates admin, then locks itself.
if (file_exists(__DIR__.'/setup.lock')) die('Setup already completed.');
require 'config/database.php';
$pdo->prepare("INSERT IGNORE INTO users(name,email,password,role) VALUES('Administrator','admin@safezone.com',?, 'admin')")
    ->execute([password_hash('SafeZone@123', PASSWORD_DEFAULT)]);
file_put_contents(__DIR__.'/setup.lock', date('c'));
echo 'Admin created. Setup is now disabled. <a href="login.php">Go to login</a>';
