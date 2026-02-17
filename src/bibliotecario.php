<?php
session_start();
require 'auth.php';

if (!utenteLoggato() || !èBibliotecario()) {
    header("Location: index.php");
    exit;
}
?>

<?php require 'header.php'; ?>

<div class="container mt-4">

<h2>Area Bibliotecario</h2>

<div class="card p-3 shadow-sm">

<p>Benvenuto nell’area riservata ai bibliotecari.</p>

<a href="restituzionibibl.php" class="btn btn-dark">
Gestione restituzioni
</a>

</div>

</div>

<?php require 'footer.php'; ?>
