<?php
$host     = getenv('MYSQLHOST')     ?: 'db';
$port     = getenv('MYSQLPORT')     ?: '3306';
$dbname   = getenv('MYSQLDATABASE') ?: 'beatdrop_db';
$username = getenv('MYSQLUSER')     ?: 'beatdrop_user';
$password = getenv('MYSQLPASSWORD') ?: 'password';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}