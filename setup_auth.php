<?php
require 'system/bootstrap.php';
$db = \Config\Database::connect();

$username = 'admin';
$password = password_hash('admin123', PASSWORD_BCRYPT);
$role = 'admin';

$db->query("REPLACE INTO users (id, username, password, role) VALUES (1, '$username', '$password', '$role')");
echo "Admin user reset successfully! (username: admin, password: admin123)\n";
