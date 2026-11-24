# Giorno 9 – Esercizi del pomeriggio (Eloquent)

Si lavora nel progetto `bookshelf`. Per provare il codice: `php artisan tinker`
(ricordati di uscire e rientrare dopo ogni modifica a un model).

---

## 9.1 – Eloquent in tinker (30 minuti)
Scrivi in `appunti-tinker.txt` le istruzioni che usi e il risultato.
1. Quanti libri ci sono? Quanti generi?
2. I titoli dei libri pubblicati prima del 1960, in ordine di anno.
3. Il libro con id 8: titolo, nome del genere, nomi degli autori.
4. Crea un genere "Fantascienza", poi eliminalo.
5. Prova a creare due generi con lo stesso nome: che errore ottieni, e quale riga della migration lo provoca?

## 9.2 – Accessor e scope (40 minuti)
1. Nel model `Book` aggiungi l'accessor `is_long`: vale `true` se il libro ha più di 400 pagine.
   Provalo: `Book::find(4)->is_long`.
2. Aggiungi lo scope `publishedBetween(int $from, int $to)` e usalo:
   `Book::publishedBetween(1960, 1970)->pluck('title')`.
3. In `Author` aggiungi lo scope `italian()` (nazionalità "italiana") e conta gli autori italiani.

## 9.3 – Relazioni in tinker (30 minuti)
1. I titoli dei libri di Italo Calvino, partendo dall'autore.
2. Il genere del libro "Il nome della rosa".
3. Quanti libri ha il genere Giallo?
4. Aggiungi un secondo autore a un libro con `attach`, poi toglilo con `detach`.
5. Assegna un altro genere a un libro con `$book->genre()->associate($genre); $book->save();`
   e verifica la colonna `genre_id` in HeidiSQL.

## 9.4 – Il model dei prestiti (50 minuti)
Usa la tabella `loans` dell'esercizio 8.5.
1. `php artisan make:model Loan -f`: `#[Fillable]` con `user_id`, `book_id`, `loaned_at`, `returned_at`;
   casts `loaned_at` e `returned_at` come `'date'`.
2. Relazioni: `Loan` appartiene a `User` e a `Book`; `Book` e `User` hanno molti `Loan` (`loans()`).
3. In tinker: crea un prestito per l'utente demo e il libro 1, poi leggi
   `User::first()->loans->first()->book->title`.

## 9.5 – Dati di prova per i prestiti (50 minuti)
1. In `LoanFactory`: `user_id` di un utente a caso, `book_id` di un libro a caso,
   `loaned_at` una data degli ultimi 6 mesi (`fake()->dateTimeBetween('-6 months')`),
   `returned_at` null nel 30% dei casi (`fake()->boolean(70) ? ... : null`).
2. In `DatabaseSeeder`, dopo i libri: `Loan::factory(20)->create();`.
3. `php artisan migrate:fresh --seed` e conta in tinker i prestiti ancora aperti:
   `Loan::whereNull('returned_at')->count()`.
4. Imposta `APP_FAKER_LOCALE=it_IT` nel `.env`, rigenera e confronta i nomi degli autori finti.
