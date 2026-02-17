#BiblioTech

Sistema di gestione biblioteca sviluppato in PHP e MySQL.

##Ruoli

###Studente
- Visualizza catalogo libri
- Prende libri in prestito
- Visualizza i propri prestiti

###Bibliotecario
- Visualizza prestiti attivi
- Regista restituzioni

##Sicurezza

- Login con sessioni
- Password hashate
- Prepared statements
- Protezione pagine

##Avvio con Docker

Avviare il progetto con:

docker compose up -d

Applicazione:
http://localhost:8090

phpMyAdmin:
http://localhost:8081

Mailpit:
http://localhost:8025

##Database
Il database viene creato automaticamente all’avvio.

##Tecnologie usate

- PHP
- MySQL
- Docker
- HTML
