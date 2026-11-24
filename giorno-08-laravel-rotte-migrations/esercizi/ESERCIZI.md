# Giorno 8 – Esercizi del pomeriggio (Laravel: rotte, viste, migrations)

Si lavora nel progetto `bookshelf` creato stamattina.

---

## 8.1 – Il progetto (20 minuti)
1. Esegui `php artisan route:list`: quante rotte ci sono? Quali hai scritto tu?
2. Cambia `APP_NAME` nel `.env` in `Bookshelf` e verifica dove compare nella pagina di benvenuto.
3. Esegui `php artisan migrate:status` e scrivi in un file `appunti.txt` l'ordine delle migrations.

## 8.2 – Rotte (40 minuti) – `routes/web.php`
1. La rotta `/anno/{anno}` risponde solo se `anno` è un numero e restituisce `Libri del 1957`.
2. La rotta `/cerca/{testo?}`: con il testo `Hai cercato: calvino`, senza `Scrivi qualcosa da cercare`.
3. Dai il nome `anno` alla prima rotta e, nella vista `saluto`, aggiungi un link
   a `/anno/1980` costruito con `route('anno', 1980)`.

## 8.3 – Viste (40 minuti)
1. Crea la vista `info` con `php artisan make:view info`: titolo "Bookshelf" e un paragrafo
   con l'anno corrente (`{{ date('Y') }}`). La rotta `/info` restituisce la vista invece del testo.
2. Crea la rotta `/libro-del-giorno` che passa a una vista `libro` un array:
   `['titolo' => 'Il barone rampante', 'autore' => 'Italo Calvino', 'anno' => 1957]`.
   La vista mostra i tre valori. Come si scrive `{{ $titolo }}` se passi l'array con la chiave
   `libro`? (`{{ $libro['titolo'] }}`)

## 8.4 – Una migration in più (40 minuti)
1. `php artisan make:migration add_language_to_books_table`: aggiungi a `books` la colonna
   `language`, testo di 30 caratteri, facoltativa, dopo `pages`
   (`Schema::table`, `->nullable()->after('pages')`).
2. `php artisan migrate`, controlla in HeidiSQL, poi annullala con `php artisan migrate:rollback`.
   Cosa fa il metodo `down()`?

## 8.5 – La tabella dei prestiti (60 minuti)
1. Migration per la tabella `loans`: `user_id` e `book_id` (chiavi esterne),
   `loaned_at` (data, obbligatoria), `returned_at` (data, facoltativa), `timestamps()`.
2. Scegli il comportamento ON DELETE di ogni chiave esterna e scrivi il perché in un commento:
   cosa deve succedere ai prestiti se si cancella un utente? E un libro?
3. `php artisan migrate` e verifica in HeidiSQL (scheda Codice della tabella: il CREATE TABLE
   generato da Laravel).
4. Confronta con il `CREATE TABLE prestiti` del giorno 4: cosa corrisponde a cosa?
