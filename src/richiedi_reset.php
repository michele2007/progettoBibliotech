<?php
session_start();
require 'db.php';
require 'mail.php';

$messaggio = "";
$tipo = ""; // success | error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = strtolower(trim($_POST['email']));

    // cerca utente
    $stmt = db()->prepare("SELECT * FROM utente WHERE email = ?");
    $stmt->execute([$email]);
    $utente = $stmt->fetch();

    if ($utente) {

        // crea token sicuro
        $token = bin2hex(random_bytes(32));

        // salva token e scadenza
        $stmt = db()->prepare("
            UPDATE utente
            SET reset_token = ?, 
                reset_scadenza = DATE_ADD(NOW(), INTERVAL 30 MINUTE)
            WHERE id_utente = ?
        ");
        $stmt->execute([$token, $utente['id_utente']]);

        // invia email
        inviaEmailReset($email, $token);

        $messaggio = "Email inviata con successo! Controlla la tua posta.";
        $tipo = "success";

    } else {
        // per sicurezza non riveliamo se l'email esiste
        $messaggio = "Se l'email è registrata riceverai un link di recupero.";
        $tipo = "info";
    }
}
?>

<?php require 'header.php'; ?>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-4">Recupero Password</h4>

<form method="POST">

<div class="mb-3">
    <label class="form-label">Inserisci la tua email</label>
    <input type="email"
           name="email"
           class="form-control"
           required>
</div>

<button class="btn btn-dark w-100">
    Invia link di recupero
</button>

</form>

<?php if($messaggio): ?>
<div class="alert alert-<?= $tipo ?> mt-3 text-center">
    <?= $messaggio ?>
</div>
<?php endif; ?>

<div class="text-center mt-3">
    <a href="login.php">Torna al login</a>
</div>

</div>
</div>

</div>
</div>
</div>

<?php require 'footer.php'; ?>
