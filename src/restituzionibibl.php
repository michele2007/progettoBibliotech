<?php
session_start();

require 'auth.php';
require 'prestiti.php';

if (!utenteLoggato() || !èBibliotecario()) {
    header("Location: index.php");
    exit;
}

// se viene cliccata la restituzione
if (isset($_GET['restituisci'])) {
    restituisciLibro((int)$_GET['restituisci']);
    header("Location: restituzionibibl.php");
    exit;
}

// recupera prestiti attivi
$prestitiAttivi = prestitiAttivi();
?>

<?php require 'header.php'; ?>

<div class="container mt-4">

<h2>Prestiti attivi</h2>

<?php if(empty($prestitiAttivi)): ?>
    <div class="alert alert-info">Nessun prestito attivo.</div>
<?php endif; ?>

<?php foreach ($prestitiAttivi as $prestito): ?>

<div class="card mb-3 p-3 shadow-sm">

<strong><?= $prestito['titolo'] ?></strong><br>
Studente: <?= $prestito['nome'] ?> <?= $prestito['cognome'] ?><br>
Data prestito: <?= $prestito['data_inizio'] ?><br><br>

<a class="btn btn-success"
   href="?restituisci=<?= $prestito['id_prestito'] ?>">
   Registra restituzione
</a>

</div>

<?php endforeach; ?>

</div>

<?php require 'footer.php'; ?>
