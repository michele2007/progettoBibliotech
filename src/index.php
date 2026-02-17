<?php
session_start();
require 'auth.php';
require 'libri.php';

if (!utenteLoggato()) {
    header("Location: login.php");
    exit;
}

$idUtente = $_SESSION['utente']['id_utente'];

if (isset($_GET['prendi'])) {
    prendiLibro((int)$_GET['prendi'], $idUtente, $idUtente);
    header("Location: index.php");
    exit;
}

$libri = elencoLibri();
?>

<?php require 'header.php'; ?>

<div class="container mt-4">
<div class="mb-3">

<?php if(èStudente()): ?>
    <a href="prestitistud.php" class="btn btn-outline-primary">
        Visualizza i miei prestiti
    </a>
<?php endif; ?>

<?php if(èBibliotecario()): ?>
    <a href="restituzionibibl.php" class="btn btn-outline-dark">
        Gestione restituzioni
    </a>
<?php endif; ?>

</div>

<h2>Catalogo Libri</h2>

<div class="row">

<?php foreach($libri as $libro): ?>

<div class="col-md-4">
<div class="card mb-3 p-3 shadow-sm">

<h5><?= $libro['titolo'] ?></h5>
Autore: <?= $libro['nome_autore'] ?><br>
Disponibili: <strong><?= $libro['copie_disponibili'] ?></strong><br><br>

<?php if(èStudente() && $libro['copie_disponibili']>0): ?>
<a class="btn btn-primary" href="?prendi=<?= $libro['id_libro'] ?>">Prendi</a>
<?php endif; ?>

</div>
</div>

<?php endforeach; ?>

</div>

</div>

<?php require 'footer.php'; ?>
