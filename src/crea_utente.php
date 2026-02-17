<?php
require 'db.php';

$password = password_hash("123", PASSWORD_DEFAULT);

$stmt = db()->prepare("
    UPDATE utente
    SET passw_hash = ?
    WHERE email = 'mario.rossi@example.com'
");

$stmt->execute([$password]);

echo "Password aggiornata";
