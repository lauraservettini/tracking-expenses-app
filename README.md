# Expenses Tracking Application

Questa è una desktop app per la gestione delle spese ed è costruita utilizzando:

- PHP
- HTML
- CSS
- MVC pattern
- composer
- dotenv
- SQL e MariaDB con phpmyadmin
- custom CSRFToken
- custom Framework
- Factory Design Pattern(Framework)
- Singleton Design Pattern(Framework,solo per il Container)

__

## Descrizione

In quest'applicazione sono implementate funzionalità di base per la sicurezza, autenticazione e autorizzazione tramite la creazione di un framework personalizzato.
Le pagine sono state create utilizzando un template enjine personalizzato.

____

### Sicurezza
Per la sicurezza sui valori provenienti dai campi input dei form è stato effettuato un "escape", inoltre è stato creato un CSRFToken personalizzato e sono stati settati i cookie params per bloccare attacchi XSS, code Injection e CSRF. In aggiunta viene rigenerato il SESSION ID ad ogni LOGIN/LOGOUT/SIGNIN e sulla password viene effettuato un criptaggio secondo l'algoritmo bcrypt prima di venire salvata nel database.
Sono state aggiunte middleware che limitino l'accesso alle routes a seconda dell'autentificazione e dell'autorizzazione.

### Logged in pages
Le funzionalità della desktop app sono visibili solo nel momento in cui si effettua il login. 
C'è infatti una dashboard nella quale poter visionare tutte le transaction relative all'utente loggato, effettuare ricerche sulla base di termini presenti nella descrizione della transazione per poterle selezionare in modo più specifico.
Dalla dashboard si può cancellare o fare un update delle info relative alla transazione selezionata; si può caricare, visionare o cancellare una o più ricevute(sotto forma di formati jpg, png o pdf).
___

### Admin pages
Sono state aggiunte delle admin routes che permettono di avere accesso a tutte le transazioni, a tutti gli user registrati e a tutte le ricevute, senza poterle modificare.

___

### MIT LICENCE 
Copyright (c) 2023 Laura Servettini
