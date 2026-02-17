<?php
require_once 'db.php';

/* CREA SESSIONE LOGIN */
function creaSessione($idUtente) {

    $stmt = db()->prepare("
        INSERT INTO sessione
        (id_utente, scadenza)
        VALUES (?, DATE_ADD(NOW(), INTERVAL 2 HOUR))
    ");

    $stmt->execute([$idUtente]);

    // salva id sessione in $_SESSION
    $_SESSION['id_sessione_db'] = db()->lastInsertId();
}


/* CHIUDE SESSIONE AL LOGOUT */
function chiudiSessione() {

    if (!isset($_SESSION['id_sessione_db'])) {
        return;
    }

    db()->prepare("
        UPDATE sessione
        SET logout = NOW()
        WHERE id_sessione = ?
    ")->execute([$_SESSION['id_sessione_db']]);
}
