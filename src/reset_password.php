<?php
require 'db.php';

$token = trim($_GET['token'] ?? '');

if ($token === '') {
    die("Token mancante");
}

// verifica token valido
$stmt = db()->prepare("
    SELECT * FROM utente
    WHERE reset_token = ?
    AND reset_scadenza > NOW()
");

$stmt->execute([$token]);
$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utente) {
    die("Link non valido o scaduto");
}

$successo = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nuovaPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    db()->prepare("
        UPDATE utente
        SET passw_hash = ?,
            reset_token = NULL,
            reset_scadenza = NULL,
            tentativi_login = 0
        WHERE id_utente = ?
    ")->execute([$nuovaPassword, $utente['id_utente']]);

    $successo = true;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-4">Reimposta Password</h4>

<?php if($successo): ?>

    <div class="alert alert-success text-center">
        Password aggiornata con successo!
    </div>

    <div class="text-center">
        <a href="login.php" class="btn btn-dark">
            Vai al login
        </a>
    </div>

<?php else: ?>

<form method="POST">

<div class="mb-3">
    <label class="form-label">Nuova password</label>
    <input type="password"
           name="password"
           class="form-control"
           required>
</div>

<button class="btn btn-dark w-100">
    Aggiorna password
</button>

</form>

<div class="text-center mt-3">
    <a href="login.php">Torna al login</a>
</div>

<?php endif; ?>

</div>
</div>

</div>
</div>
</div>

</body>
</html>
