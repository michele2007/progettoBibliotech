<?php
session_start();
require 'auth.php';
require 'db.php';
require 'mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $utente = trovaUtente($email);

    if ($utente && !empty($utente['passw_hash']) &&
        password_verify($password, $utente['passw_hash'])) {

        // reset tentativi login
        db()->prepare("
            UPDATE utente SET tentativi_login = 0
            WHERE id_utente = ?
        ")->execute([$utente['id_utente']]);

        $_SESSION['utente'] = $utente;

        require 'sessioni.php';
        creaSessione($utente['id_utente']);

        header("Location: index.php");
        exit;
    }

    // se utente esiste aumenta tentativi
    if ($utente) {

        db()->prepare("
            UPDATE utente
            SET tentativi_login = tentativi_login + 1
            WHERE id_utente = ?
        ")->execute([$utente['id_utente']]);

        $utente['tentativi_login']++;

        if ($utente['tentativi_login'] >= 3) {

            $token = bin2hex(random_bytes(32));

            db()->prepare("
                UPDATE utente
                SET reset_token = ?, 
                    reset_scadenza = DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                WHERE id_utente = ?
            ")->execute([$token, $utente['id_utente']]);

            inviaEmailReset($utente['email'], $token);

            $errore = "Troppi tentativi. Controlla la mail.";
        } else {
            $errore = "Email o password errati.";
        }

    } else {
        $errore = "Email o password errati.";
    }
}
?>

<?php require 'header.php'; ?>

<div class="container mt-5" style="max-width:400px">
<div class="card p-4 shadow">
<h3 class="text-center mb-3">Login</h3>

<form method="POST">
<input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
<button class="btn btn-dark w-100">Accedi</button>

<div class="text-center mt-3">
    <a href="richiedi_reset.php">Password dimenticata?</a>
</div>
</form>

<?php if(isset($errore)): ?>
<div class="alert alert-danger mt-3"><?= $errore ?></div>
<?php endif; ?>

</div>
</div>

<?php require 'footer.php'; ?>
