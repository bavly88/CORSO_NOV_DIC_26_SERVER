# Giorno 9 – Esercizi del pomeriggio (Eloquent) – versione spiegata

Oggi lavori nel progetto `bookshelf` con **Eloquent**, la parte di Laravel che legge e scrive il database usando classi PHP invece di scrivere SQL.
Provi il codice in **tinker** e scrivi model, accessor, scope, relazioni e dati di prova.

Ogni esercizio ha questa forma:

- la richiesta;
- una spiegazione di cosa serve, con un esempio;
- i passi da seguire uno alla volta;
- le **trappole**, cioè gli errori più comuni;
- il **Controllo**, cioè come capire se funziona.

> **Importante: gli esempi mostrano la forma, non sono la soluzione.**
> Gli esempi parlano di un'applicazione diversa, inventata: un'azienda con i model `Employee` (dipendente), `Department` (reparto) e `Trip` (trasferta).
> Devi adattare la forma ai model di `bookshelf`.

---

## Prima di iniziare

### 1. Controlla i dati

Nel terminale, dalla cartella `bookshelf`:

```bash
php artisan migrate:fresh --seed
```

Questo comando **cancella tutte le tabelle**, riesegue tutte le migrations e poi i seeder.
Tutti i dati inseriti a mano vanno persi. Ripartono i dati della mattina:

- l'utente demo (`demo@example.com`, password `password`);
- i 15 libri reali e i loro autori;
- 5 autori finti, ognuno con 3 libri finti.

### 2. Cos'è tinker

**tinker** è una console: scrivi un'istruzione PHP, premi Invio, vedi subito il risultato.
Dentro tinker i model si usano senza `use`: `Book::count()` funziona così.

```bash
php artisan tinker
```

Per uscire: `exit`.

**Regola importante:** tinker legge le classi solo quando parte.
Dopo ogni modifica a un model: `exit`, poi di nuovo `php artisan tinker`.

Se tinker non mostra il prompt nel Git Bash fuori da VS Code: `winpty php artisan tinker`.

### 3. Dove stanno i file

| Cosa | Cartella |
|---|---|
| Model | `app/Models/` |
| Factory | `database/factories/` |
| Seeder | `database/seeders/` |
| Migrations | `database/migrations/` |

La cartella `lezione/bookshelf` del corso contiene i file come sono alla fine della mattina.

---

## Promemoria Eloquent

Un **model** è una classe che rappresenta una tabella.
`Book` rappresenta la tabella `books`.
Ogni oggetto `Book` è una riga.

| Istruzione | Cosa fa | SQL simile |
|---|---|---|
| `Employee::count()` | conta le righe | `SELECT COUNT(*)` |
| `Employee::all()` | tutte le righe | `SELECT *` |
| `Employee::find(5)` | la riga con id 5, oppure `null` | `WHERE id = 5` |
| `Employee::where('salary', '>', 2000)` | aggiunge una condizione | `WHERE salary > 2000` |
| `->orderBy('hired_on')` | ordina | `ORDER BY hired_on` |
| `->get()` | esegue la query, restituisce una **collection** | |
| `->first()` | esegue la query, restituisce la prima riga | `LIMIT 1` |
| `->pluck('last_name')` | prende una sola colonna | `SELECT last_name` |
| `Employee::create([...])` | inserisce una riga | `INSERT` |
| `$employee->delete()` | cancella la riga | `DELETE` |

Una **collection** è una lista di oggetti, con molti metodi utili (`count()`, `pluck()`, `first()`...).

**Attenzione:** `where(...)` e `orderBy(...)` preparano la query ma non la eseguono.
La query parte con `get()`, `first()`, `count()`, `pluck()`.

---

## 9.1 – Eloquent in tinker (30 minuti)

### La richiesta

Scrivi in `appunti-tinker.txt` le istruzioni che usi e il risultato.

1. Quanti libri ci sono? Quanti generi?
2. I titoli dei libri pubblicati prima del 1960, in ordine di anno.
3. Il libro con id 8: titolo, nome del genere, nomi degli autori.
4. Crea un genere "Fantascienza", poi eliminalo.
5. Prova a creare due generi con lo stesso nome: che errore ottieni, e quale riga della migration lo provoca?

### Cosa ti serve

**Più istruzioni in catena.**

```php
Employee::where('hired_on', '<', '2020-01-01')->orderBy('hired_on')->pluck('last_name')
```

**Leggere una colonna di una riga.**

```php
$e = Employee::find(3);
$e->last_name
```

**Leggere attraverso una relazione.**
Se il model ha una relazione (per esempio `department()`), la usi come proprietà senza parentesi:

```php
$e->department->name                    // il nome del reparto del dipendente
$e->projects->pluck('title')            // i titoli dei progetti (relazione molti a molti)
```

Le relazioni di `Book` sono già scritte nel model. Apri `app/Models/Book.php` e leggile.
Per i nomi degli autori, `Author` ha l'attributo `full_name`.

**Creare ed eliminare.**

```php
$d = Department::create(['name' => 'Logistica']);
$d->delete();
```

### Passi

1. Apri `appunti-tinker.txt` in VS Code accanto al terminale.
2. Per ogni punto: scrivi l'istruzione in tinker, copia istruzione e risultato nel file.
3. Al punto 5: esegui due volte lo stesso `create`. Leggi l'errore. Poi apri la migration dei generi.

### Trappole

- Senza `get()` o `pluck()` alla fine, tinker mostra un oggetto `Builder`, non i dati.
- `Book::find(8)->genre()` (con le parentesi) restituisce la relazione, non il genere. Per il genere: senza parentesi.
- Il nome della colonna è in inglese: `year`, non `anno`. Controlla la migration dei libri.
- Al punto 4, se dimentichi di eliminare il genere, resta nel database.

### Domanda del punto 5: dove guardare

Leggi il codice di errore SQL e la parola chiave nel messaggio.
Poi cerca, nella migration dei generi, la riga che parla della colonna del nome.

### Controllo

- Libri: 30 (15 reali e 15 finti).
- Generi: 5.
- Prima del 1960: almeno i 5 libri reali; i libri finti hanno anni a caso, quindi il numero può cambiare dopo ogni `migrate:fresh --seed`.
- Libro 8: "Il nome della rosa", Giallo, Umberto Eco.
- Dopo il punto 4, `Genre::count()` è di nuovo 5.

---

## 9.2 – Accessor e scope (40 minuti)

### La richiesta

1. Nel model `Book` aggiungi l'accessor `is_long`: vale `true` se il libro ha più di 400 pagine.
   Provalo: `Book::find(4)->is_long`.
2. Aggiungi lo scope `publishedBetween(int $from, int $to)` e usalo:
   `Book::publishedBetween(1960, 1970)->pluck('title')`.
3. In `Author` aggiungi lo scope `italian()` (nazionalità "italiana") e conta gli autori italiani.

### Cosa ti serve

**Accessor.**
Un **accessor** è un attributo calcolato: non è una colonna del database, ma si legge come se lo fosse.
Il modello è `full_name` in `app/Models/Author.php`.

```php
protected function isSenior(): Attribute
{
    return Attribute::make(
        get: fn () => $this->hired_on->year < 2010,
    );
}
```

Uso: `$employee->is_senior`.

Regola dei nomi:

- il metodo si scrive in **camelCase**: `isSenior`;
- l'attributo si legge in **snake_case**: `is_senior`.

**Scope.**
Uno **scope** è una condizione con un nome, da riusare nelle query.
Il modello è `search` in `app/Models/Book.php`.

```php
#[Scope]
protected function earningMoreThan(Builder $query, int $amount): void
{
    $query->where('salary', '>', $amount);
}
```

Uso: `Employee::earningMoreThan(2000)->get()`.

- Il primo parametro (`$query`) lo passa Laravel.
- I parametri dopo sono quelli che scrivi tu tra le parentesi.
- Lo scope non restituisce niente: aggiunge condizioni a `$query`.

**Due condizioni insieme.**
Puoi chiamare `where` due volte, oppure usare `whereBetween('colonna', [$da, $a])`.

### Passi

1. Apri `Book.php`. Aggiungi l'accessor. Controlla i `use` in cima al file: `Attribute` è già importato in `Author.php` ma forse non in `Book.php`.
2. Esci e rientra in tinker. Prova `Book::find(4)->is_long` e un libro corto.
3. Aggiungi lo scope in `Book.php`. Esci e rientra in tinker. Prova.
4. Apri `Author.php`. Aggiungi lo scope. Controlla i `use`: servono `Scope` e `Builder` (guarda in cima a `Book.php`).
5. Esci e rientra in tinker. Conta.

### Trappole

- Manca il `use` di `Attribute`, `Scope` o `Builder`: errore `Class "App\Models\Attribute" not found` (o simile).
- Il metodo si chiama `isLong`, ma in tinker si legge `is_long`. `$book->isLong` non funziona.
- Dimentichi di uscire e rientrare in tinker: il nuovo metodo "non esiste".
- Uno scope senza parametri (`italian`) ha comunque il parametro `$query`.
- "più di 400" si scrive `> 400`. Un libro di esattamente 400 pagine non è lungo.

### Controllo

- `Book::find(4)->is_long` → `true` (660 pagine).
- `Book::find(5)->is_long` → `false` (400 pagine esatte).
- `Book::publishedBetween(1960, 1970)->pluck('title')` contiene almeno i 4 libri reali di quegli anni, più eventuali libri finti.
- Il conteggio degli italiani è almeno 8 (gli autori reali italiani), più gli autori finti con nazionalità "italiana".

---

## 9.3 – Relazioni in tinker (30 minuti)

### La richiesta

1. I titoli dei libri di Italo Calvino, partendo dall'autore.
2. Il genere del libro "Il nome della rosa".
3. Quanti libri ha il genere Giallo?
4. Aggiungi un secondo autore a un libro con `attach`, poi toglilo con `detach`.
5. Assegna un altro genere a un libro con `$book->genre()->associate($genre); $book->save();` e verifica la colonna `genre_id` in HeidiSQL.

### Cosa ti serve

**Trovare una riga per nome.**

```php
$d = Department::where('name', 'Vendite')->first();
```

**Le relazioni del progetto.**

| Model | Relazione | Tipo |
|---|---|---|
| `Genre` | `books()` | un genere ha molti libri (`hasMany`) |
| `Book` | `genre()` | un libro appartiene a un genere (`belongsTo`) |
| `Book` | `authors()` | molti a molti (`belongsToMany`) |
| `Author` | `books()` | molti a molti (`belongsToMany`) |

**Con o senza parentesi.**

| Scrittura | Cosa restituisce |
|---|---|
| `$d->employees` | i dati collegati (collection o oggetto) |
| `$d->employees()` | la relazione: puoi aggiungere condizioni o chiamare metodi come `attach`, `associate` |

**`attach` e `detach`.**
Lavorano sulla tabella pivot di una relazione molti a molti.

```php
$employee->projects()->attach(7);   // aggiunge la riga (employee, progetto 7)
$employee->projects()->detach(7);   // la toglie
```

**`associate`.**
Lavora su una relazione `belongsTo`.
Imposta la chiave esterna. Poi serve `save()` per scrivere nel database.

### Passi

1. Trova l'autore Calvino. Poi leggi i titoli dei suoi libri.
2. Trova il libro per titolo. Poi leggi il nome del genere.
3. Trova il genere Giallo. Conta i suoi libri.
4. Scegli un libro e un autore. `attach`. Controlla gli autori del libro. `detach`. Controlla di nuovo.
5. Scegli un libro e un genere. `associate` e `save`. Controlla in HeidiSQL (F5 sulla tabella `books`).

### Trappole

- Il cognome sta in `last_name`. `where('name', 'Calvino')` non trova niente.
- `where(...)` senza `first()` restituisce un Builder, non l'autore.
- Dopo `attach`, se rileggi `$book->authors` (senza parentesi) puoi vedere ancora i dati vecchi: Eloquent li ha già caricati. Usa `$book->authors()->get()`, oppure `$book->refresh()`.
- `attach` due volte con lo stesso autore: errore `Duplicate entry`, perché la chiave primaria della pivot è la coppia dei due id.
- Senza `save()` dopo `associate`, nel database non cambia niente.

### Controllo

- Calvino: 3 titoli.
- "Il nome della rosa": Giallo.
- Giallo: almeno 4 libri (i reali), più eventuali libri finti.
- Dopo `detach` il libro ha di nuovo gli autori di prima.
- In HeidiSQL `genre_id` del libro è l'id del genere che hai scelto.

---

## 9.4 – Il model dei prestiti (50 minuti)

### La richiesta

Usa la tabella `loans` dell'esercizio 8.5.

1. `php artisan make:model Loan -f`: `#[Fillable]` con `user_id`, `book_id`, `loaned_at`, `returned_at`; casts `loaned_at` e `returned_at` come `'date'`.
2. Relazioni: `Loan` appartiene a `User` e a `Book`; `Book` e `User` hanno molti `Loan` (`loans()`).
3. In tinker: crea un prestito per l'utente demo e il libro 1, poi leggi `User::first()->loans->first()->book->title`.

### Cosa ti serve

**Il comando.**
`make:model Loan -f` crea due file:

- `app/Models/Loan.php`: il model;
- `database/factories/LoanFactory.php`: la factory (`-f`), che userai nella 9.5.

**`#[Fillable]`.**
Elenca i campi che si possono riempire con `create([...])`.
Il modello è in cima a `Book.php` e `Author.php`.
Se un campo non è nell'elenco, `create` lo ignora.

**Casts.**
Un **cast** dice a Laravel di che tipo è una colonna.
Con il cast `'date'`, la colonna arriva come oggetto data (Carbon) invece che come testo.
Così puoi scrivere per esempio `$trip->started_on->format('d/m/Y')`.
Il modello è il metodo `casts()` in `Book.php`.

**Relazioni 1-N, nei due versi.**

```php
// in Trip: una trasferta appartiene a un dipendente
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class);
}

// in Employee: un dipendente ha molte trasferte
public function trips(): HasMany
{
    return $this->hasMany(Trip::class);
}
```

Il modello è la coppia `Genre::books()` e `Book::genre()`.

**Il model `User`.**
Sta in `app/Models/User.php` ed esiste già: lo ha creato Laravel.
Aggiungi lì il metodo `loans()`.

### Passi

1. Esegui il comando del punto 1.
2. In `Loan.php`: aggiungi `#[Fillable(...)]` sopra la classe e il metodo `casts()`. Copia i `use` necessari da `Book.php`.
3. In `Loan.php`: aggiungi le due relazioni `belongsTo`.
4. In `Book.php` e in `User.php`: aggiungi `loans()`. Controlla che ci sia `use Illuminate\Database\Eloquent\Relations\HasMany;`.
5. Rientra in tinker. Crea il prestito con `Loan::create([...])`. Per l'utente demo usa l'id di `User::first()`. Per la data puoi usare `now()` o una data tra apici, per esempio `'2026-11-01'`.
6. Esegui l'istruzione del punto 3.

### Trappole

- Se la tabella `loans` non esiste (per esempio perché nella 8.5 c'è stato un errore), la `create` dà `Table 'bookshelf.loans' doesn't exist`. Controlla con `php artisan migrate:status`.
- Il nome della tabella viene ricavato dal nome del model: `Loan` → `loans`. Se nella 8.5 l'hai chiamata diversamente, Laravel non la trova.
- Un campo che manca in `#[Fillable]` resta vuoto. Se è obbligatorio (per esempio `loaned_at`), la `create` dà errore `Field 'loaned_at' doesn't have a default value`.
- `User::first()->loans` è una collection: per prendere un prestito serve `->first()`.
- Ricordati di uscire e rientrare in tinker dopo aver modificato i model.

### Controllo

- L'istruzione del punto 3 restituisce `"Il barone rampante"`.
- `Loan::first()->loaned_at` è un oggetto data (tinker mostra `Illuminate\Support\Carbon`), non un testo.
- `Book::find(1)->loans->count()` è 1.
- In HeidiSQL la tabella `loans` ha la riga, con `created_at` e `updated_at` riempiti.

---

## 9.5 – Dati di prova per i prestiti (50 minuti)

### La richiesta

1. In `LoanFactory`: `user_id` di un utente a caso, `book_id` di un libro a caso, `loaned_at` una data degli ultimi 6 mesi (`fake()->dateTimeBetween('-6 months')`), `returned_at` null nel 30% dei casi (`fake()->boolean(70) ? ... : null`).
2. In `DatabaseSeeder`, dopo i libri: `Loan::factory(20)->create();`.
3. `php artisan migrate:fresh --seed` e conta in tinker i prestiti ancora aperti: `Loan::whereNull('returned_at')->count()`.
4. Imposta `APP_FAKER_LOCALE=it_IT` nel `.env`, rigenera e confronta i nomi degli autori finti.

### Cosa ti serve

**Cos'è una factory.**
Una **factory** descrive come creare una riga finta.
Il metodo `definition()` restituisce un array: una chiave per colonna, con un valore inventato.
`fake()` genera valori a caso: nomi, numeri, date.

**Il modello.**
Apri `database/factories/BookFactory.php`.
La riga di `genre_id` prende l'id di un genere **esistente**, scelto a caso.
Per `user_id` e `book_id` ti serve la stessa forma.

**L'operatore ternario.**
`condizione ? valore_se_vero : valore_se_falso`

```php
'left_on' => fake()->boolean(80) ? fake()->date() : null,
```

`fake()->boolean(80)` restituisce `true` nell'80% dei casi.

**Una data che dipende da un'altra (facoltativo).**
La richiesta non lo chiede, ma un prestito non può essere restituito prima di essere fatto.
Puoi calcolare la data del prestito in una variabile **prima** del `return`, e poi usarla per la restituzione:

```php
public function definition(): array
{
    $start = fake()->dateTimeBetween('-1 year');
    return [
        'started_on' => $start,
        'ended_on'   => fake()->dateTimeBetween($start),
    ];
}
```

`dateTimeBetween($start)` genera una data tra `$start` e oggi.

**L'ordine nel seeder.**
`DatabaseSeeder` esegue le istruzioni in ordine.
I prestiti hanno bisogno di utenti e libri già esistenti: vanno creati dopo.

### Passi

1. Apri `LoanFactory.php` (creata nella 9.4 con `-f`).
2. Scrivi le quattro chiavi in `definition()`. Copia la forma da `BookFactory.php` per gli id.
3. Apri `DatabaseSeeder.php`. Aggiungi la riga del punto 2 in fondo al metodo `run()`. Aggiungi `use App\Models\Loan;` in cima.
4. `php artisan migrate:fresh --seed`.
5. In tinker: `Loan::count()` e l'istruzione del punto 3.
6. Annota i cognomi di alcuni autori finti: `Author::latest('id')->take(5)->pluck('last_name')`.
7. Aggiungi la riga nel `.env`. Riesegui il comando del passo 4.
8. Esegui di nuovo l'istruzione del passo 6. Confronta.

### Trappole

- Il prestito creato a mano nella 9.4 sparisce con `migrate:fresh`. È normale.
- Se la factory cerca un utente esistente ma la tabella degli utenti è vuota, il valore è `null` e la riga dà errore. Nel seeder l'utente demo viene creato per primo: controlla di aver messo i prestiti dopo.
- Se nel seeder i prestiti sono **prima** del `call(BookshelfSeeder::class)`, i libri non esistono ancora.
- Manca `use Illuminate\Database\Eloquent\Factories\HasFactory;` e `use HasFactory;` nel model `Loan`: errore `Call to undefined method App\Models\Loan::factory()`. Con `make:model` di solito ci sono già: controlla.
- Dopo aver cambiato il `.env`, se i nomi restano in inglese: `php artisan config:clear` e riprova.
- La restituzione deve venire dopo il prestito. Se le due date sono generate in modo indipendente, alcune restituzioni risultano prima del prestito.

### Controllo

- `Loan::count()` → 20.
- I prestiti aperti sono circa 6 (il 30% di 20). Il numero cambia a ogni rigenerazione: tra 2 e 10 è normale.
- Facoltativo: nessun prestito ha `returned_at` prima di `loaned_at`. Puoi controllare in HeidiSQL con una SELECT, oppure in tinker con `Loan::whereColumn('returned_at', '<', 'loaned_at')->count()`, che deve dare 0.
- Dopo il passo 7, i cognomi degli autori finti sono italiani.

---

## Errori frequenti

| Messaggio o sintomo | Causa probabile |
|---|---|
| `Class "Book" not found` in tinker | Hai scritto male il nome, oppure il file del model ha un errore. Esci e rientra in tinker. |
| Il metodo nuovo "non esiste" in tinker | tinker non è stato riavviato dopo la modifica del model. |
| `Call to undefined method ...::isLong()` o simili | Hai chiamato l'accessor come metodo. Si legge come proprietà: `->is_long`. |
| `Class "App\Models\Attribute" not found` (o `Scope`, `Builder`, `HasMany`, `BelongsTo`) | Manca il `use` in cima al file del model. |
| tinker mostra `Illuminate\Database\Eloquent\Builder` | Manca `get()`, `first()`, `pluck()` o `count()` alla fine. |
| `Attempt to read property "name" on null` | La riga cercata non esiste (`find` o `first` hanno restituito `null`), oppure il libro non ha genere. |
| `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry` | Valore duplicato in una colonna `unique`, oppure `attach` di una coppia già presente. |
| `SQLSTATE[HY000]: General error: 1364 Field 'xxx' doesn't have a default value` | La colonna è obbligatoria e non le hai dato un valore, oppure il campo non è in `#[Fillable]`. |
| `Add [xxx] to fillable property to allow mass assignment` | Il campo non è in `#[Fillable]`. |
| `SQLSTATE[42S02]: Base table or view not found: 1146 Table 'bookshelf.loans' doesn't exist` | La migration dei prestiti non è stata eseguita, oppure la tabella ha un altro nome. |
| `SQLSTATE[23000]: ... Cannot add or update a child row: a foreign key constraint fails` | Hai usato un `user_id` o un `book_id` che non esiste. |
| `Call to undefined method App\Models\Loan::factory()` | Manca `use HasFactory;` nel model. |
| `migrate:fresh --seed` si ferma a metà | Leggi il messaggio: di solito è un errore in una factory o nell'ordine del seeder. |
| tinker non mostra il prompt | Git Bash fuori da VS Code: usa `winpty php artisan tinker`. |
