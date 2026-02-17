<?php
$pdo = new PDO("mysql:host=localhost;dbname=biblioteca", "root", "");

$sql = file_get_contents("Bibliotech.sql");

$pdo->exec($sql);

echo "Database creato!";
?>