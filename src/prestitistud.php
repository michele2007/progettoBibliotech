<?php
session_start();
require 'auth.php';
require 'prestiti.php';

if (!utenteLoggato()) {
    header("Location: login.php");
    exit;
}

$idUtente = $_SESSION['utente']['id_utente'];

/* richiesta restituzione */
if (isset($_GET['restituisci'])) {
    richiediRestituzione((int)$_GET['restituisci']);
    header("Location: prestitistud.php");
    exit;
}

$prestiti = prestitiStudente($idUtente);
?>

<?php require 'header.php'; ?>

<div class="container mt-4">

<h2>I miei prestiti</h2>

<?php if(empty($prestiti)): ?>
    <div class="alert alert-info">
        Non hai prestiti attivi.
    </div>
<?php endif; ?>

<?php foreach ($prestiti as $p): ?>

<div class="card mb-3 p-3 shadow-sm">

<strong><?= $p['titolo'] ?></strong><br>

Data inizio: <?= $p['data_inizio'] ?><br>
Scadenza: <strong><?= $p['data_scadenza'] ?></strong><br>

<?php if(prestitoScaduto($p['data_scadenza']) && !$p['data_fine']): ?>
    <span class="text-danger fw-bold">PRESTITO SCADUTO</span><br>
<?php endif; ?>

<?php if($p['data_fine']): ?>

    Restituito il: <?= $p['data_fine'] ?>

<?php else: ?>

     In corso<br><br>

    <?php if(!$p['richiesta_restituzione']): ?>
        <a class="btn btn-warning"
           href="?restituisci=<?= $p['id_prestito'] ?>">
           restituisci
        </a>
    <?php else: ?>
        <span class="badge bg-warning text-dark">
            In attesa di conferma bibliotecario
        </span>
    <?php endif; ?>

<?php endif; ?>

</div>

<?php endforeach; ?>

<a href="index.php" class="btn btn-secondary">Torna al catalogo</a>

</div>

<?php require 'footer.php'; ?>
