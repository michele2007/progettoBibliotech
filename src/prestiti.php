<?php
require_once 'db.php';


/*
========================================
 PRESTITI DELLO STUDENTE
========================================
*/
function prestitiStudente($idStudente) {

    $stmt = db()->prepare("
        SELECT
            p.id_prestito,
            l.titolo,
            p.data_inizio,
            p.data_scadenza,
            p.data_fine,
            p.richiesta_restituzione
        FROM prestito p
        JOIN libro l ON p.id_libro = l.id_libro
        WHERE p.id_studente = ?
        ORDER BY p.data_scadenza ASC
    ");

    $stmt->execute([$idStudente]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
========================================
 PRESTITI ATTIVI (BIBLIOTECARIO)
========================================
*/
function prestitiAttivi() {

    $stmt = db()->query("
        SELECT
            p.id_prestito,
            u.nome,
            u.cognome,
            l.titolo,
            p.data_inizio,
            p.data_scadenza,
            p.richiesta_restituzione
        FROM prestito p
        JOIN utente u ON p.id_studente = u.id_utente
        JOIN libro l ON p.id_libro = l.id_libro
        WHERE p.data_fine IS NULL
        ORDER BY p.data_scadenza ASC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
========================================
 RESTITUZIONE DEFINITIVA
========================================
*/
function restituisciLibro($idPrestito) {

    $pdo = db();
    $pdo->beginTransaction();

    // segna restituzione
    $pdo->prepare("
        UPDATE prestito
        SET data_fine = NOW(),
            richiesta_restituzione = 0
        WHERE id_prestito = ?
    ")->execute([$idPrestito]);

    // aumenta copie disponibili
    $pdo->prepare("
        UPDATE libro
        SET copie_disponibili = copie_disponibili + 1
        WHERE id_libro = (
            SELECT id_libro FROM prestito WHERE id_prestito = ?
        )
    ")->execute([$idPrestito]);

    $pdo->commit();
}


/*
========================================
 CONTROLLO PRESTITO GIÀ ATTIVO
========================================
*/
function prestitoGiaAttivo($idStudente, $idLibro) {

    $stmt = db()->prepare("
        SELECT COUNT(*) 
        FROM prestito
        WHERE id_studente = ?
        AND id_libro = ?
        AND data_fine IS NULL
    ");

    $stmt->execute([$idStudente, $idLibro]);

    return $stmt->fetchColumn() > 0;
}


/*
========================================
 RICHIESTA RESTITUZIONE STUDENTE
========================================
*/
function richiediRestituzione($idPrestito) {

    db()->prepare("
        UPDATE prestito
        SET richiesta_restituzione = 1
        WHERE id_prestito = ?
    ")->execute([$idPrestito]);
}


/*
========================================
 VERIFICA SE IL PRESTITO È SCADUTO
========================================
*/
function prestitoScaduto($dataScadenza) {
    return strtotime($dataScadenza) < time();
}
