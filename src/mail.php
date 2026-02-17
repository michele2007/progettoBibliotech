<?php
function inviaEmailReset($email, $token) {

    $link = "http://localhost:8090/reset_password.php?token=$token";

    $messaggio = "Hai richiesto il reset della password.\n\n";
    $messaggio .= "Clicca il link qui sotto:\n$link\n\n";
    $messaggio .= "Il link scade tra 30 minuti.";

    mail($email, "Reset Password", $messaggio);
}
