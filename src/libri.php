<?php
require_once 'db.php';
require_once 'prestiti.php';

function elencoLibri() {
    return db()->query("SELECT * FROM libro")
               ->fetchAll(PDO::FETCH_ASSOC);
}
function prendiLibro($idLibro, $idStudente, $idBibliotecario) {

    if (prestitoGiaAttivo($idStudente, $idLibro)) {
        return false;
    }

    $pdo = db();
    $pdo->beginTransaction();

    // inserisce prestito con scadenza a 2 mesi
    $pdo->prepare("
        INSERT INTO prestito
        (id_studente, id_bibliotecario, id_libro, data_scadenza)
        VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 2 MONTH))
    ")->execute([$idStudente, $idBibliotecario, $idLibro]);

    // riduce copie disponibili
    $pdo->prepare("
        UPDATE libro
        SET copie_disponibili = copie_disponibili - 1
        WHERE id_libro = ? AND copie_disponibili > 0
    ")->execute([$idLibro]);

    $pdo->commit();
    return true;
}

