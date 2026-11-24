# Giorno 8 – Laravel 1: progetto, rotte, viste, migrations

Riferimento: dispensa "Laravel - applicazioni web MVC", capitoli 1, 2 e 3.
La cartella `bookshelf/` qui accanto contiene **solo i file scritti oggi**, con il loro
percorso nel progetto: servono per confrontare o recuperare, non per essere copiati all'inizio.

| Ora | Passo | Dispensa |
|---|---|---|
| 9:00 | Cos'è Laravel, MVC. Creare il progetto | 1, 1.2 |
| | `composer create-project laravel/laravel bookshelf` (qualche minuto: intanto si spiega la struttura) | |
| 9:30 | `CREATE DATABASE bookshelf;` in HeidiSQL, il `.env` (vedi `bookshelf/env-database.txt`), `php artisan migrate`, `php artisan about` | 1.3 |
| 9:55 | `php artisan serve`, il secondo terminale. Struttura delle cartelle, Artisan, il ciclo di una richiesta | 1.4-1.7 |
| 10:30 | Pausa | |
| 10:45 | Rotte con closure e parametri | 2.1, 2.2 |
| 11:05 | `php artisan make:view saluto`, passare dati alla vista, rotte con nome | 2.3, 2.4 |
| 11:35 | Migrations: `php artisan make:migration create_genres_table` (poi authors, books, author_book, **in quest'ordine**) | 3.1-3.5 |
| 12:15 | `php artisan migrate`, controllo in HeidiSQL, `migrate:status`, `migrate:rollback` | 3.6 |
| 12:30 | Margine per domande e ripasso | |

Cosa deve funzionare alla fine della mattina:
- http://localhost:8000/ciao/Anna mostra la vista con il nome;
- in HeidiSQL il database `bookshelf` ha le tabelle `genres`, `authors`, `books`, `author_book`
  oltre a quelle di Laravel.

Attenzione in aula virtuale:
- `composer create-project` scarica molti file: farlo creare a tutti subito, prima della spiegazione;
- il progetto **non** va in una cartella di OneDrive;
- se `php artisan migrate` crea le tabelle in SQLite, il `.env` non è stato salvato o è sbagliato:
  `php artisan about` mostra il database in uso.
