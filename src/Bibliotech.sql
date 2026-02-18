SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS bibliotech;
USE bibliotech;

CREATE TABLE utente (
    id_utente INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    passw VARCHAR(255) NOT NULL,
    passw_hash VARCHAR(255) NULL UNIQUE,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    ruolo ENUM('bibliotecario', 'studente') NOT NULL,
    tentativi_login INT DEFAULT 0,
    reset_token VARCHAR(255) NULL,
    reset_scadenza DATETIME NULL
);

CREATE TABLE libro (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    copie_totali INT NOT NULL,
    copie_disponibili INT NOT NULL,
    nome_autore VARCHAR(100) NOT NULL,
    titolo VARCHAR(255) NOT NULL
);


CREATE TABLE prestito (
    id_prestito INT AUTO_INCREMENT PRIMARY KEY,

    id_studente INT NOT NULL,
    id_bibliotecario INT NOT NULL,
    id_libro INT NOT NULL,

    data_inizio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- data entro cui restituire (2 mesi)
    data_scadenza DATE NOT NULL,

    -- data restituzione effettiva
    data_fine DATETIME NULL,

    -- richiesta restituzione dallo studente
    richiesta_restituzione TINYINT DEFAULT 0,

    FOREIGN KEY (id_studente) REFERENCES utente(id_utente) ON DELETE CASCADE,
    FOREIGN KEY (id_bibliotecario) REFERENCES utente(id_utente) ON DELETE CASCADE,
    FOREIGN KEY (id_libro) REFERENCES libro(id_libro) ON DELETE CASCADE
);

CREATE TABLE sessione (
    id_sessione INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT NOT NULL,
    inizio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    scadenza DATETIME NOT NULL,
    logout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utente) REFERENCES utente(id_utente) ON DELETE CASCADE
);

INSERT INTO utente (nome, cognome, email, passw, ruolo)
VALUES
('Mario', 'Rossi', 'mario.rossi@example.com', '123', 'studente'),
('Franco', 'Bianchi', 'franco.bianchi@example.com', '123', 'studente'),
('Angelo', 'Paciuli', 'angelo.paciuli@example.com', '123', 'bibliotecario');

INSERT INTO libro (copie_totali, copie_disponibili, nome_autore, titolo)
VALUES
(20, 20, 'George Orwell', '1984'),
(20, 20, 'J.R.R. Tolkien', 'Il Signore degli Anelli'),
(20, 20, 'Umberto Eco', 'Il Nome della Rosa'),
(20, 20, 'Antoine de Saint-Exupéry', 'Il Piccolo Principe'),
(20, 20, 'J.K. Rowling', 'Harry Potter e la Pietra Filosofale');
