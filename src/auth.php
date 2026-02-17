<?php
require_once 'db.php';

function trovaUtente($email) {
    $sql = "SELECT * FROM utente WHERE email = ?";
    $stmt = db()->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function utenteLoggato() {
    return isset($_SESSION['utente']);
}

function èStudente() {
    return $_SESSION['utente']['ruolo'] === 'studente';
}

function èBibliotecario() {
    return $_SESSION['utente']['ruolo'] === 'bibliotecario';
}
