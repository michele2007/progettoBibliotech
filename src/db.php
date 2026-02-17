<?php

if (!function_exists('db')) {

function db() {

    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=db;dbname=bibliotech;charset=utf8mb4",
                "root",
                "rootpassword"
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Connessione DB fallita");
        }
    }

    return $pdo;
}

}
