# Giorno 3 – Esercizi del pomeriggio (OOP e Composer)

## Prima di iniziare

1. Crea una cartella `php-giorno-03`. Tutti gli esercizi vanno lì dentro.
2. Gli esercizi da 3.1 a 3.4 sono script da eseguire nel terminale. Ogni esercizio ha un suo file:

   ```bash
   php es-3-1.php
   ```

3. L'esercizio 3.5 è un piccolo progetto Composer, in una sottocartella a parte.

---

## 3.1 – La classe Autore (30 minuti)

**File:** `es-3-1.php`

1. Crea la classe `Autore`. Il costruttore riceve tre valori:
   - `nome` (stringa)
   - `cognome` (stringa)
   - `annoNascita` (intero)

   Usa la *constructor promotion*: dichiari le proprietà direttamente nei parametri del costruttore.

   ```php
   public function __construct(
       public string $nome,
       ...
   ) {}
   ```

2. Aggiungi il metodo `nomeCompleto(): string`.
   Restituisce nome e cognome separati da uno spazio, per esempio `"Italo Calvino"`.

3. Aggiungi il metodo `etaOggi(): int`.
   Restituisce l'anno di oggi meno l'anno di nascita. Calcolalo anche se l'autore non è più vivo.
   *Ti serve:* `date('Y')` restituisce l'anno corrente.

4. Crea tre autori, mettili in un array e, con un `foreach`, stampa una riga per ognuno:

   ```
   Italo Calvino, nato nel 1923
   ```

---

## 3.2 – Un Libro che si difende (40 minuti)

**File:** `es-3-2.php`

Un oggetto "si difende" quando non permette di essere creato o usato con dati sbagliati.

1. Crea la classe `Libro` con queste proprietà:
   - `titolo` (stringa)
   - `autore` (stringa: qui è solo un nome, non un oggetto `Autore`)
   - `pagine` (intero)
   - `copie` (intero, valore predefinito 1). Questa proprietà è **private**: dall'esterno non si può modificare direttamente.

2. Nel costruttore controlla i dati. Lancia una `InvalidArgumentException` se:
   - il titolo è una stringa vuota, oppure
   - le pagine sono 0 o meno.

   ```php
   throw new InvalidArgumentException("Il titolo non può essere vuoto");
   ```

3. Aggiungi questi metodi:

   | Metodo | Cosa fa |
   |---|---|
   | `presta(): void` | toglie una copia. Se le copie sono già 0, lancia `RuntimeException("Nessuna copia disponibile")` |
   | `restituisci(): void` | aggiunge una copia |
   | `disponibile(): bool` | restituisce `true` se c'è almeno una copia |
   | `copie(): int` | restituisce il numero di copie |

4. Prova la classe con `try / catch`. Fai due prove:
   - crea un libro con 0 pagine;
   - crea un libro con una copia e chiama `presta()` due volte.

   Nel `catch` stampa il messaggio dell'eccezione (`$e->getMessage()`).
   **Lo script deve arrivare fino in fondo**: dopo le due prove stampa una riga finale, per esempio `Fine dello script`. Se non la vedi, un'eccezione non è stata catturata.

---

## 3.3 – La biblioteca (60 minuti)

**File:** `es-3-3.php`

Copia in cima al file la classe `Libro` del 3.2. Sotto scrivi la nuova classe.

1. Crea la classe `Biblioteca` con una proprietà privata `$libri`, che all'inizio è un array vuoto.

2. Aggiungi questi metodi:

   | Metodo | Cosa fa |
   |---|---|
   | `aggiungi(Libro $libro): void` | aggiunge il libro all'array |
   | `numeroLibri(): int` | restituisce quanti libri ci sono |
   | `cercaPerAutore(string $autore): array` | restituisce un array con gli oggetti `Libro` di quell'autore |
   | `totalePagine(): int` | restituisce la somma delle pagine di tutti i libri |
   | `disponibili(): array` | restituisce un array con i soli libri che hanno almeno una copia |

   *Ti serve:* `count()`, `foreach`. Se le conosci, anche `array_filter()` e `array_sum()`.

3. Prova la classe:
   - crea una biblioteca;
   - aggiungi almeno 5 libri, con almeno due libri dello stesso autore;
   - presta uno o più libri finché almeno uno resta senza copie;
   - chiama ogni metodo e stampa il risultato.

   `cercaPerAutore()` e `disponibili()` restituiscono oggetti: per stamparli usa un `foreach` e scrivi il titolo di ogni libro.

---

## 3.4 – Ebook e audiolibri (30 minuti)

**File:** `es-3-4.php`

1. Apri il file della lezione `02-ereditarieta.php` e copia nel tuo file la classe `Libro` e la classe `Ebook`.
   Non usare il `Libro` del 3.2: è una classe diversa.

2. Crea la classe `Audiolibro`, che estende `Libro`:
   - aggiungi la proprietà `durataMinuti` (intero);
   - riscrivi il metodo `descrizione()`: restituisce la descrizione di `Libro` più la durata in ore e minuti.

   Esempio con `durataMinuti = 500`:

   ```
   ... (audiolibro, 8 ore e 20 minuti)
   ```

   *Ti serve:* `parent::descrizione()`, `intdiv()` per le ore, `%` per i minuti.

3. Crea un libro, un ebook e un audiolibro e mettili nello stesso array.
   Stampa la descrizione di ognuno con **un solo** `foreach`, senza `if` per distinguere il tipo.
   Ogni oggetto usa automaticamente la sua versione di `descrizione()`.

---

## 3.5 – Con Composer (40 minuti)

**Cartella:** `php-giorno-03/biblioteca-composer`

Obiettivo: le classi stanno ognuna nel suo file e Composer le carica da solo, senza `require` scritti a mano.

1. Crea la cartella `biblioteca-composer` e dentro un file `composer.json`:

   ```json
   {
       "autoload": {
           "psr-4": {
               "App\\": "src/"
           }
       }
   }
   ```

   Vuol dire: le classi del namespace `App` stanno nella cartella `src`.

2. Crea questi due file e spostaci le classi del 3.3:

   | Classe | File |
   |---|---|
   | `Libro` | `src/Modelli/Libro.php` |
   | `Biblioteca` | `src/Modelli/Biblioteca.php` |

   La prima riga dopo `<?php` di entrambi i file è:

   ```php
   namespace App\Modelli;
   ```

   Il nome del file deve essere uguale al nome della classe, maiuscole comprese.

3. Dal terminale, dentro la cartella `biblioteca-composer`, esegui:

   ```bash
   composer dump-autoload
   ```

   Si crea la cartella `vendor` con il file `autoload.php`.

4. Crea `esegui.php` nella cartella `biblioteca-composer`:

   ```php
   <?php
   require __DIR__ . '/vendor/autoload.php';

   use App\Modelli\Libro;
   use App\Modelli\Biblioteca;

   // qui ripeti le prove del 3.3
   ```

   Eseguilo con `php esegui.php`.

5. Esperimento: rinomina `Libro.php` in `libro.php` (tutto minuscolo) e riesegui `esegui.php`.
   - Funziona ancora?
   - Scrivi in un commento in `esegui.php` cosa è successo.

   Nota: Windows non distingue maiuscole e minuscole nei nomi dei file, un server Linux sì. Pensa a cosa succederebbe pubblicando il progetto su un server Linux.

   Alla fine rimetti il nome `Libro.php`.
