# Giorno 8 – Esercizi del pomeriggio (Laravel: rotte, viste, migrations) – versione spiegata

Oggi lavori nel progetto Laravel `bookshelf` creato stamattina.
Gli esercizi riguardano i tre argomenti della mattina: rotte, viste, migrations.

Ogni esercizio ha questa forma:

- la richiesta;
- una spiegazione di cosa serve, con un esempio;
- i passi da seguire uno alla volta;
- le **trappole**, cioè gli errori più comuni;
- il **Controllo**, cioè come capire se funziona.

> **Importante: gli esempi mostrano la forma, non sono la soluzione.**
> Gli esempi parlano di un'applicazione diversa, inventata: un'azienda con dipendenti, reparti e trasferte.
> Devi adattare la forma al progetto `bookshelf`.

---

## Prima di iniziare

### 1. Apri il progetto

Apri in VS Code la cartella `bookshelf` creata stamattina.
Il progetto non deve stare in una cartella di OneDrive.

### 2. Due terminali

Ti servono due terminali (Git Bash), tutti e due **nella cartella `bookshelf`**:

- **Terminale 1**: `php artisan serve`. Avvia il server su `http://localhost:8000`. Resta occupato: non scriverci altro.
- **Terminale 2**: per tutti gli altri comandi `php artisan ...`.

Per aprire il secondo terminale in VS Code: pulsante **+** nel pannello del terminale.

### 3. Controlla il database

In HeidiSQL deve esistere il database `bookshelf`, con le tabelle `genres`, `authors`, `books`, `author_book` e quelle di Laravel (`users`, `migrations`, ...).
Se non ci sono, nel terminale 2:

```bash
php artisan about
```

Nella sezione **Drivers** o **Database** leggi quale database sta usando Laravel.
Deve essere `mariadb`.
Se è `sqlite`, il file `.env` non è stato salvato o è sbagliato: confrontalo con `lezione/bookshelf/env-database.txt`.

### 4. Dove stanno i file

| Cosa | Cartella |
|---|---|
| Rotte | `routes/web.php` |
| Viste | `resources/views/` (file `.blade.php`) |
| Migrations | `database/migrations/` |
| Configurazione | `.env` (nella cartella principale del progetto) |

La cartella `lezione/bookshelf` del corso contiene i file scritti stamattina.
Se un tuo file non funziona, confrontalo con quello.

---

## Promemoria: i comandi Artisan di oggi

**Artisan** è il programma a riga di comando di Laravel.
Si usa sempre dalla cartella del progetto, con `php artisan` seguito dal comando.

| Comando | Cosa fa |
|---|---|
| `php artisan serve` | avvia il server di sviluppo |
| `php artisan route:list` | elenca tutte le rotte |
| `php artisan make:view nome` | crea il file `resources/views/nome.blade.php` |
| `php artisan make:migration nome` | crea un file di migration vuoto |
| `php artisan migrate` | esegue le migrations non ancora eseguite |
| `php artisan migrate:status` | mostra quali migrations sono state eseguite |
| `php artisan migrate:rollback` | annulla l'ultimo gruppo di migrations eseguite |
| `php artisan about` | mostra la configurazione del progetto |

---

## 8.1 – Il progetto (20 minuti)

### La richiesta

1. Esegui `php artisan route:list`: quante rotte ci sono? Quali hai scritto tu?
2. Cambia `APP_NAME` nel `.env` in `Bookshelf` e verifica dove compare nella pagina di benvenuto.
3. Esegui `php artisan migrate:status` e scrivi in un file `appunti.txt` l'ordine delle migrations.

### Cosa ti serve

**Leggere `route:list`.**
Ogni riga è una rotta.
Si leggono il **metodo** (GET, POST...), l'**indirizzo** (URI) e, a destra, il nome della rotta o cosa la gestisce.
In fondo c'è il totale.

Alcune rotte le crea Laravel da solo.
Le tue sono quelle che trovi scritte in `routes/web.php`.

**Il file `.env`.**
`.env` contiene la configurazione del progetto, una riga per impostazione: `NOME=valore`.
Se il valore contiene spazi, va tra virgolette: `APP_NAME="Il mio sito"`.

**`migrate:status`.**
Mostra un elenco di migrations con lo stato (`Ran` = eseguita) e il numero di **batch**.
Un batch è un gruppo di migrations eseguite con lo stesso comando `migrate`.

L'ordine delle migrations dipende dal nome del file: ogni nome comincia con data e ora.
Laravel le esegue in ordine di nome.

### Passi

1. Nel terminale 2: `php artisan route:list`. Conta le righe e leggi il totale.
2. Apri `routes/web.php`. Per ogni rotta del file, trova la riga corrispondente nell'elenco.
3. Apri `.env`, cambia `APP_NAME`, salva.
4. Ricarica `http://localhost:8000` nel browser.
5. Nel terminale 2: `php artisan migrate:status`.
6. Crea `appunti.txt` nella cartella principale del progetto e scrivi l'ordine.

### Trappole

- Al punto 2 il nome può non comparire nel testo della pagina. Cerca anche nella scheda del browser e nel codice della pagina (**Ctrl+U**).
- Se hai eseguito in passato `php artisan config:cache`, le modifiche al `.env` non hanno effetto. Esegui `php artisan config:clear`.
- Se il server non vede la modifica, fermalo (**Ctrl+C**) e riavvialo.

### Controllo

- Sai dire quante rotte ci sono e quali sono tue.
- Hai trovato almeno un punto dove compare "Bookshelf".
- In `appunti.txt` le quattro migrations della mattina compaiono in quest'ordine: genres, authors, books, author_book. Prima di loro ci sono quelle di Laravel.

---

## 8.2 – Rotte (40 minuti) – `routes/web.php`

### La richiesta

1. La rotta `/anno/{anno}` risponde solo se `anno` è un numero e restituisce `Libri del 1957`.
2. La rotta `/cerca/{testo?}`: con il testo `Hai cercato: calvino`, senza `Scrivi qualcosa da cercare`.
3. Dai il nome `anno` alla prima rotta e, nella vista `saluto`, aggiungi un link a `/anno/1980` costruito con `route('anno', 1980)`.

### Cosa ti serve

**Una rotta.**
Una **rotta** collega un indirizzo a un pezzo di codice.
Quando il browser chiede quell'indirizzo, Laravel esegue il codice e manda al browser quello che restituisce.

```php
Route::get('/reparti', fn () => 'Elenco dei reparti');
```

`fn () => ...` è una **funzione freccia**: una funzione breve che restituisce il valore scritto dopo `=>`.

**Un parametro.**
Una parte dell'indirizzo tra graffe `{...}` è un **parametro**.
Il suo valore arriva alla funzione.

```php
Route::get('/reparto/{codice}', fn (string $codice) => "Reparto $codice");
```

`/reparto/VEN` restituisce `Reparto VEN`.

**Solo numeri.**
`->whereNumber('nome')` fa rispondere la rotta solo se il parametro è un numero.
Con un valore diverso, Laravel risponde 404.
Guarda la rotta `/libri/{id}` in `routes/web.php`.

**Parametro facoltativo.**
Un `?` dopo il nome lo rende facoltativo: `{codice?}`.
La funzione deve dare un valore di partenza al parametro: `string $codice = null` oppure `?string $codice = null`.
Guarda la rotta `/pagina/{numero?}`.

Per restituire due testi diversi serve un `if` o l'operatore ternario.
Con un `if` la funzione freccia non basta: serve una funzione normale.

```php
Route::get('/reparto/{codice?}', function (?string $codice = null) {
    if ($codice === null) {
        return 'Nessun reparto indicato';
    }
    return "Reparto $codice";
});
```

**Nome di una rotta e `route()`.**
`->name('...')` dà un nome alla rotta.
`route('nome')` costruisce l'indirizzo partendo dal nome.
Se la rotta ha un parametro, il valore si passa come secondo argomento:

```php
Route::get('/reparto/{codice}', ...)->name('reparto');
```

```blade
<a href="{{ route('reparto', 'VEN') }}">Vendite</a>
```

Il vantaggio: se un giorno cambi l'indirizzo della rotta, i link si aggiornano da soli.
La vista `saluto` usa già `route('info')`.

### Passi

1. Scrivi la rotta del punto 1 senza `whereNumber`. Prova `/anno/1957` e `/anno/abc`.
2. Aggiungi `whereNumber`. Riprova `/anno/abc`.
3. Scrivi la rotta del punto 2. Prova `/cerca/calvino` e `/cerca`.
4. Aggiungi il nome alla rotta del punto 1.
5. Apri `resources/views/saluto.blade.php` e aggiungi il link.
6. Apri `/ciao/Anna` e clicca il link.

### Trappole

- Il testo da restituire contiene il valore del parametro: usa le virgolette doppie `"..."`, non gli apici `'...'`. Con gli apici, `$anno` resta scritto così.
- Il nome nel parametro dell'indirizzo e il nome della variabile nella funzione devono essere uguali.
- `whereNumber` e `name` vanno scritti dopo la parentesi chiusa di `Route::get(...)`, prima del `;`.
- Se usi `route('anno', 1980)` prima di dare il nome alla rotta: errore `Route [anno] not defined`.
- Rotte con lo stesso inizio: Laravel usa la prima che corrisponde all'indirizzo.

### Controllo

- `/anno/1957` → `Libri del 1957`.
- `/anno/abc` → pagina 404.
- `/cerca/calvino` → `Hai cercato: calvino`.
- `/cerca` → `Scrivi qualcosa da cercare`.
- `php artisan route:list`: accanto a `anno/{anno}` compare il nome `anno`.
- Su `/ciao/Anna`, il link porta a `/anno/1980` e mostra `Libri del 1980`.

---

## 8.3 – Viste (40 minuti)

### La richiesta

1. Crea la vista `info` con `php artisan make:view info`: titolo "Bookshelf" e un paragrafo con l'anno corrente (`{{ date('Y') }}`).
   La rotta `/info` restituisce la vista invece del testo.
2. Crea la rotta `/libro-del-giorno` che passa a una vista `libro` un array:
   `['titolo' => 'Il barone rampante', 'autore' => 'Italo Calvino', 'anno' => 1957]`.
   La vista mostra i tre valori.
   Come si scrive `{{ $titolo }}` se passi l'array con la chiave `libro`? (`{{ $libro['titolo'] }}`)

### Cosa ti serve

**Una vista.**
Una **vista** è il file che contiene l'HTML della pagina.
In Laravel le viste si scrivono con **Blade**, il sistema di modelli di Laravel.
I file finiscono in `.blade.php` e stanno in `resources/views/`.

**Stampare un valore.**
In Blade si stampa con le doppie graffe: `{{ ... }}`.
Blade applica da solo `htmlspecialchars`: è la stessa protezione della funzione `e()` dei giorni 6 e 7.

**Restituire una vista da una rotta.**
`view('nome')` restituisce la vista `resources/views/nome.blade.php`.
Il nome si scrive senza `.blade.php`.

**Passare dati alla vista.**
Il secondo argomento di `view()` è un array.
Ogni chiave diventa una variabile nella vista.

```php
Route::get('/dipendente-del-mese', function () {
    return view('dipendente', ['nome' => 'Rita Bassi', 'reparto' => 'Vendite']);
});
```

Nella vista `dipendente.blade.php`:

```blade
<p>{{ $nome }} lavora in {{ $reparto }}.</p>
```

**Un array dentro una chiave.**
Se passi tutto dentro una sola chiave, nella vista hai una sola variabile, che è un array:

```php
return view('dipendente', ['persona' => ['nome' => 'Rita Bassi', 'reparto' => 'Vendite']]);
```

```blade
<p>{{ $persona['nome'] }} lavora in {{ $persona['reparto'] }}.</p>
```

### Passi

1. Nel terminale 2: `php artisan make:view info`. Apri il file creato.
2. Scrivi l'HTML: puoi partire da `saluto.blade.php`.
3. In `routes/web.php` cambia la rotta `/info`: deve restituire la vista. Lascia il nome `info`.
4. Prova `/info`.
5. Crea la vista `libro` e la rotta `/libro-del-giorno` con l'array del punto 2.
6. Prova. Poi cambia la rotta: passa lo stesso array con la chiave `libro`. Cambia la vista di conseguenza.

### Trappole

- `view('info.blade.php')` è sbagliato: si scrive `view('info')`.
- Il nome della variabile nella vista è la **chiave** dell'array, con `$` davanti.
- Se nella vista usi una variabile che non hai passato: errore `Undefined variable $...`.
- Quando cambi la rotta `/info`, non togliere `->name('info')`: la vista `saluto` usa quel nome.

### Controllo

- `/info` mostra "Bookshelf" e l'anno corrente.
- Il link "Informazioni" nella pagina `/ciao/Anna` porta ancora a `/info`.
- `/libro-del-giorno` mostra titolo, autore e anno.
- Dopo il passo 6 la pagina mostra la stessa cosa di prima.

---

## 8.4 – Una migration in più (40 minuti)

### La richiesta

1. `php artisan make:migration add_language_to_books_table`: aggiungi a `books` la colonna `language`, testo di 30 caratteri, facoltativa, dopo `pages` (`Schema::table`, `->nullable()->after('pages')`).
2. `php artisan migrate`, controlla in HeidiSQL, poi annullala con `php artisan migrate:rollback`.
   Cosa fa il metodo `down()`?

### Cosa ti serve

**Cos'è una migration.**
Una **migration** è un file PHP che descrive una modifica alla struttura del database.
Ha due metodi:

- `up()`: la modifica da fare. Viene eseguito da `migrate`.
- `down()`: come annullarla. Viene eseguito da `migrate:rollback`.

**Creare una tabella e modificarla.**

| Per | Si usa |
|---|---|
| creare una tabella nuova | `Schema::create('nome', function (Blueprint $table) { ... })` |
| modificare una tabella esistente | `Schema::table('nome', function (Blueprint $table) { ... })` |

**Aggiungere e togliere una colonna.**

```php
public function up(): void
{
    Schema::table('employees', function (Blueprint $table) {
        $table->string('phone', 20)->nullable()->after('last_name');
    });
}

public function down(): void
{
    Schema::table('employees', function (Blueprint $table) {
        $table->dropColumn('phone');
    });
}
```

- `string('phone', 20)`: colonna di testo (VARCHAR) di 20 caratteri.
- `nullable()`: la colonna può essere vuota (NULL), cioè facoltativa.
- `after('last_name')`: la colonna va messa dopo `last_name`.
- `dropColumn('phone')`: toglie la colonna.

**Il nome della migration.**
Se il nome è nella forma `add_..._to_books_table`, Laravel capisce che vuoi modificare `books` e scrive già `Schema::table('books', ...)` nei due metodi.
Apri il file e controlla.

### Passi

1. Nel terminale 2: il comando `make:migration` del punto 1.
2. Apri il file nuovo in `database/migrations/` (è l'ultimo dell'elenco).
3. Scrivi la colonna in `up()`.
4. Scrivi in `down()` l'istruzione che la toglie.
5. `php artisan migrate`.
6. In HeidiSQL: F5, tabella `books`, scheda Struttura. Cerca la colonna.
7. `php artisan migrate:rollback`.
8. In HeidiSQL: F5 e guarda di nuovo.

### Trappole

- Se `down()` è vuoto, il rollback non dà errore ma la colonna resta.
- `migrate:rollback` annulla l'ultimo **batch** intero, non solo l'ultima migration. Se hai eseguito più migrations con lo stesso `migrate`, le annulla tutte.
- Il file della migration resta anche dopo il rollback. Al prossimo `migrate` (per esempio nella 8.5) verrà eseguito di nuovo.
- Se modifichi una migration già eseguita, la modifica non ha effetto. Prima fai il rollback, poi modifica, poi `migrate`.

### Domanda del punto 2: dove guardare

Guarda la tabella `books` in HeidiSQL dopo il passo 5 e dopo il passo 8.
Esegui `php artisan migrate:status` dopo il passo 5 e dopo il passo 8: cosa cambia nella riga della tua migration?

### Controllo

- Dopo il passo 5: `language` compare tra `pages` e la colonna successiva, tipo `varchar(30)`, con NULL permesso.
- Dopo il passo 8: `language` non c'è più.

---

## 8.5 – La tabella dei prestiti (60 minuti)

### La richiesta

1. Migration per la tabella `loans`: `user_id` e `book_id` (chiavi esterne), `loaned_at` (data, obbligatoria), `returned_at` (data, facoltativa), `timestamps()`.
2. Scegli il comportamento ON DELETE di ogni chiave esterna e scrivi il perché in un commento: cosa deve succedere ai prestiti se si cancella un utente? E un libro?
3. `php artisan migrate` e verifica in HeidiSQL (scheda Codice della tabella: il CREATE TABLE generato da Laravel).
4. Confronta con il `CREATE TABLE prestiti` del giorno 4: cosa corrisponde a cosa?

### Cosa ti serve

**Il modello.**
Le migrations della mattina sono il modello.
`create_books_table` contiene due chiavi esterne. `create_author_book_table` ne contiene altre due.

**Chiave esterna in Laravel.**

```php
$table->foreignId('department_id')->constrained();
```

In una riga:

- `foreignId('department_id')`: crea la colonna `department_id`, numero intero positivo grande (BIGINT UNSIGNED).
- `constrained()`: crea la chiave esterna verso la tabella `departments`, colonna `id`. Laravel ricava il nome della tabella dal nome della colonna.

**Il comportamento ON DELETE.**
Si aggiunge dopo `constrained()`:

| Laravel | SQL | Cosa succede alle righe collegate |
|---|---|---|
| `->cascadeOnDelete()` | `ON DELETE CASCADE` | vengono cancellate |
| `->nullOnDelete()` | `ON DELETE SET NULL` | la chiave esterna diventa NULL |
| `->restrictOnDelete()` | `ON DELETE RESTRICT` | la cancellazione è vietata |

Se non scrivi niente, il comportamento è RESTRICT.

**Le date.**

```php
$table->date('hired_on');               // obbligatoria
$table->date('left_on')->nullable();    // facoltativa
```

**`timestamps()`.**
Aggiunge due colonne: `created_at` e `updated_at`.
Laravel le riempie da solo.

**Commenti in PHP.**
Un commento su una riga comincia con `//`.

### Passi

1. `php artisan make:migration create_loans_table`.
2. Nel file nuovo, `up()` contiene già `Schema::create('loans', ...)` con `id()` e `timestamps()`. Aggiungi le altre colonne tra le due.
3. Per ogni chiave esterna rispondi alla domanda del punto 2. Scegli il comportamento. Scrivi il commento sopra la riga.
4. `php artisan migrate`.
5. In HeidiSQL: F5, tabella `loans`, scheda **Codice CREATE** (o "Codice"). Leggi il CREATE TABLE.
6. Apri il tuo file del giorno 4 con il `CREATE TABLE prestiti`. Mettili uno accanto all'altro.

### Domande del punto 2: su cosa ragionare

- Un prestito è uno **storico**: dice chi ha preso cosa, e quando. Vuoi perdere lo storico?
- Ha senso un prestito senza utente o senza libro?
- Se scegli `nullOnDelete`, la colonna deve poter essere vuota. Guarda le colonne `genre_id` e `user_id` in `create_books_table`: cosa hanno in più?
- Puoi scegliere comportamenti diversi per le due chiavi. Rileggi lo schema del database `biblioteca` del giorno 5: cosa era stato scelto per `prestiti`?

### Trappole

- La tabella dei prestiti deve essere creata **dopo** `users` e `books`: la chiave esterna punta a tabelle che devono già esistere. Con `make:migration` adesso, il file ha la data di oggi: va bene.
- `nullOnDelete()` senza `nullable()`: la migration dà errore.
- Se nella 8.4 hai fatto il rollback, il prossimo `migrate` riesegue anche la migration di `language`. È normale.
- Se `migrate` si ferma a metà con un errore, la tabella può essere stata creata senza chiavi esterne. Correggi, poi esegui `php artisan migrate:rollback` (o cancella la tabella in HeidiSQL) e riprova.

### Domanda del punto 4: cosa confrontare

Fai una tabella a due colonne: a sinistra la riga di Laravel, a destra la riga SQL corrispondente.
Guarda in particolare:

- il tipo delle colonne `id` e delle chiavi esterne;
- come si chiamano i vincoli (`CONSTRAINT ...`) che Laravel ha generato;
- cosa compare dopo `ON DELETE`;
- le colonne che ci sono in una tabella e non nell'altra.

### Controllo

- In HeidiSQL la tabella `loans` ha 7 colonne: `id`, `user_id`, `book_id`, `loaned_at`, `returned_at`, `created_at`, `updated_at`.
- Nella scheda del CREATE TABLE ci sono due `FOREIGN KEY`, con il comportamento che hai scelto.
- `returned_at` permette NULL, `loaned_at` no.
- `php artisan migrate:status` mostra la migration dei prestiti come eseguita.

---

## Errori frequenti

| Messaggio o sintomo | Causa probabile |
|---|---|
| `Could not open input file: artisan` | Il terminale non è nella cartella del progetto. Usa `cd bookshelf`. |
| `Failed to listen on 127.0.0.1:8000 (reason: Address already in use)` | Il server è già avviato in un altro terminale. Usa quello, oppure fermalo con Ctrl+C. |
| `404 Not Found` | Rotta non scritta, scritta con un indirizzo diverso, oppure parametro non numerico con `whereNumber`. Controlla con `php artisan route:list`. |
| `Route [xxx] not defined` | Il nome usato in `route('xxx')` non esiste. Controlla `->name(...)`. |
| `View [xxx] not found` | Il file della vista non esiste o ha un nome diverso. Deve stare in `resources/views/` e finire in `.blade.php`. |
| `Undefined variable $xxx` in una vista | La rotta non passa quella chiave nell'array di `view()`. |
| `syntax error, unexpected ...` in `web.php` | Manca un `;`, una parentesi o una virgola. Guarda la riga indicata e quella prima. |
| `SQLSTATE[HY000] [2002]` | MariaDB non è avviato. |
| `SQLSTATE[HY000] [1045] Access denied` | Utente o password sbagliati nel `.env`. |
| `SQLSTATE[HY000] [1049] Unknown database 'bookshelf'` | Il database non esiste: `CREATE DATABASE bookshelf;` in HeidiSQL. |
| Le tabelle vengono create in SQLite | Il `.env` ha ancora `DB_CONNECTION=sqlite`, oppure non è stato salvato. |
| `Nothing to migrate.` | Tutte le migrations sono già state eseguite. Se hai modificato un file già eseguito, fai prima il rollback. |
| `SQLSTATE[42S21]: Column already exists: 1060 Duplicate column name` | La colonna esiste già: la migration è stata eseguita prima, o la colonna è stata aggiunta a mano. |
| `SQLSTATE[42S01]: Base table or view already exists: 1050` | La tabella esiste già. Fai il rollback o cancellala in HeidiSQL. |
| `SQLSTATE[HY000]: General error: 1005 Can't create table ... (errno: 150 "Foreign key constraint is incorrectly formed")` | La tabella a cui punta la chiave esterna non esiste ancora, o il tipo della colonna è diverso. Oppure `nullOnDelete()` su una colonna non `nullable()`. |
| Modifiche al `.env` senza effetto | Riavvia `php artisan serve`. Se non basta: `php artisan config:clear`. |
