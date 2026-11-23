# Giorno 7 – Esercizi del pomeriggio (sessioni, login, sicurezza, upload) – versione spiegata

Oggi aggiungi al catalogo le funzioni della lezione: preferiti, login, protezione dei form, messaggi, copertine.
Alla fine cerchi gli errori di sicurezza in una pagina scritta male.

Ogni esercizio ha questa forma:

- la richiesta;
- una spiegazione di cosa serve, con un esempio;
- i passi da seguire uno alla volta;
- le **trappole**, cioè gli errori più comuni;
- il **Controllo**, cioè come capire se funziona.

> **Importante: gli esempi mostrano la forma, non sono la soluzione.**
> Gli esempi parlano di un sito diverso, inventato: un negozio con prodotti e un carrello, oppure un'azienda con i dipendenti.
> Devi adattare la forma al catalogo dei libri.

---

## Prima di iniziare

### 1. Prepara la cartella

1. Copia la cartella `esercizi/partenza`.
2. Rinomina la copia in `catalogo`.

È il catalogo completo del giorno 6 (autori, nuovo autore, modifica, elimina).
La differenza è il file `db.php`: ora contiene anche le funzioni della lezione di oggi.

Apri `db.php` e leggilo tutto. Le funzioni nuove sono:

| Funzione | Cosa fa |
|---|---|
| `session_start()` | avvia la sessione. Sta in cima a `db.php`, quindi vale per tutte le pagine |
| `redirect($url)` | manda il browser a un'altra pagina e ferma lo script |
| `utenteCorrente()` | restituisce i dati dell'utente loggato, oppure `null` se nessuno è loggato |
| `richiediLogin()` | se nessuno è loggato, manda a `login.php`; altrimenti restituisce l'utente |
| `csrfCampo()` | restituisce il campo nascosto con il token CSRF, da mettere nei form |
| `csrfVerifica()` | controlla il token arrivato con il POST; se è sbagliato, ferma tutto con il codice 419 |

### 2. Prepara il database

In HeidiSQL esegui `00-utenti.sql` (lo trovi anche nella cartella `partenza`) con **F9**.
Il file:

- crea la tabella `utenti`;
- crea la tabella `commenti`;
- aggiunge alla tabella `libri` la colonna `copertina`.

Poi premi **F5** nell'albero per vedere le tabelle nuove.

Attenzione:

- Se esegui `00-utenti.sql` due volte, compare l'errore `Duplicate column name 'copertina'`: la colonna esiste già. Non è grave: le tabelle sono state ricreate, la colonna c'era già.
- Se riesegui i file `00` del giorno 5, il database viene ricreato da zero e le tabelle di oggi spariscono. Dopo, riesegui anche `00-utenti.sql`.

### 3. Avvia il server

Nel terminale di VS Code (Git Bash), **dalla cartella `catalogo`**:

```bash
php -S localhost:8000
```

Apri `http://localhost:8000`.

### 4. Come vedere la sessione nel browser

La **sessione** è un insieme di dati che PHP conserva **sul server** tra una richiesta e l'altra.
Il browser riceve solo un codice, in un cookie chiamato `PHPSESSID`.
A ogni richiesta il browser rimanda il cookie, e PHP ritrova i dati in `$_SESSION`.

Per vedere il cookie: **F12 > Application > Cookies** (in Chrome) oppure **F12 > Archiviazione > Cookie** (in Firefox).

Per ripartire con una sessione nuova: apri una finestra in incognito, oppure cancella il cookie.

Per guardare cosa c'è in sessione durante le prove, puoi scrivere temporaneamente in una pagina:

```php
<pre><?php print_r($_SESSION); ?></pre>
```

Toglilo quando hai finito.

---

## 7.1 – I preferiti in sessione (30 minuti)

### La richiesta

1. Nel dettaglio del libro, un form POST con il pulsante "Aggiungi ai preferiti": aggiunge l'id del libro a `$_SESSION['preferiti']` (senza doppioni) e torna al dettaglio.
2. Pagina `preferiti.php`: l'elenco dei titoli dei libri preferiti (una query per ogni id, con `prepare`), più un pulsante "Svuota".
3. Nella testata, il link "Preferiti (3)" con il numero dei preferiti.

### Cosa ti serve

**Un array in sessione.**
`$_SESSION` è un array come gli altri.
Una sua chiave può contenere un altro array.

```php
// all'inizio il carrello può non esistere: si parte da un array vuoto
$carrello = $_SESSION['carrello'] ?? [];
$carrello[] = $idProdotto;          // aggiunge un elemento in fondo
$_SESSION['carrello'] = $carrello;  // si salva di nuovo in sessione
```

**Senza doppioni.**
`in_array($valore, $array)` restituisce `true` se il valore è già nell'array.
Aggiungi l'id solo se non c'è ancora.

**Contare gli elementi.**
`count($array)` restituisce quanti elementi ha l'array.
Se la chiave in sessione non esiste ancora, usa `?? []` per non avere errori.

**Svuotare.**
Per svuotare, assegni un array vuoto alla chiave, oppure la cancelli con `unset(...)`.

**Una query per ogni id.**
In `preferiti.php` fai un `foreach` sugli id.
Per ogni id esegui una SELECT preparata, come in `libro.php`, e prendi il titolo.

```php
$stmt = db()->prepare("SELECT id, nome FROM prodotti WHERE id = ?");
foreach ($idProdotti as $id) {
    $stmt->execute([$id]);
    $prodotto = $stmt->fetch();
    // ...
}
```

La query si prepara una volta sola, prima del ciclo. Dentro il ciclo si esegue con valori diversi.

### Passi

1. Decidi chi riceve il POST del pulsante "Aggiungi ai preferiti". Puoi gestirlo in `libro.php` stesso o in un file a parte. In entrambi i casi l'id del libro deve arrivare con il form (campo nascosto) o con l'indirizzo.
2. Scrivi il form in `libro.php`. Fai attenzione a `method="post"`.
3. Scrivi il blocco POST: leggi l'id, trasformalo in intero, aggiungilo alla sessione senza doppioni, poi `redirect(...)` al dettaglio.
4. Prova: premi il pulsante due volte sullo stesso libro. Controlla la sessione con `print_r`.
5. Crea `preferiti.php` con l'elenco dei titoli. Ogni titolo può essere un link al dettaglio.
6. Aggiungi in `preferiti.php` il form "Svuota" e il suo blocco POST.
7. In `parti/testata.php` aggiungi il link con il numero tra parentesi.

### Trappole

- Gli id che arrivano dal form sono testo (`"8"`). Se li salvi come testo e poi confronti con numeri, `in_array` funziona lo stesso, ma è più ordinato trasformarli in intero con `(int)` prima di salvarli.
- `$_SESSION['preferiti'][] = $id;` senza controllo aggiunge doppioni.
- Dopo il POST serve il redirect. Senza, ricaricando la pagina il browser rimanda il form.
- Se un libro preferito è stato eliminato, la sua query non trova niente: `fetch()` restituisce `false`. Controlla prima di stampare il titolo.

### Controllo

- Aggiungi 3 libri diversi: la testata dice "Preferiti (3)".
- Aggiungi di nuovo uno dei 3: resta "Preferiti (3)".
- `preferiti.php` mostra i 3 titoli.
- "Svuota": la testata dice "Preferiti (0)", la pagina è vuota.
- In una finestra in incognito i preferiti sono vuoti: la sessione è diversa.

---

## 7.2 – Login nel catalogo (60 minuti)

### La richiesta

1. Copia nel catalogo `registrazione.php`, `login.php`, `logout.php` della lezione, e adatta la testata: Accedi e Registrati per chi non è loggato, il nome e "Esci" per chi lo è.
2. Le pagine `nuovo.php`, `modifica.php`, `elimina.php`, `nuovo-autore.php` sono riservate: `richiediLogin()` come prima istruzione dopo il `require`.
3. Nel dettaglio del libro, i link Modifica ed Elimina compaiono solo a chi è loggato.
4. Prova ad aprire `nuovo.php` senza login: dove arrivi?

### Cosa ti serve

**Come funziona il login della lezione.**

1. `registrazione.php` salva l'utente con `password_hash(...)`. Nel database non c'è mai la password, solo il suo **hash**: una stringa da cui non si può risalire alla password.
2. `login.php` cerca l'utente per email e controlla la password con `password_verify(...)`.
3. Se è giusta, salva in `$_SESSION['utente']` l'id e il nome.
4. `utenteCorrente()` legge `$_SESSION['utente']`. Se c'è, l'utente è loggato.
5. `logout.php` svuota la sessione.

**Mostrare una parte di pagina solo a chi è loggato.**

```php
<?php if (utenteCorrente()) { ?>
    <a href="profilo.php">Il mio profilo</a>
<?php } else { ?>
    <a href="login.php">Accedi</a>
<?php } ?>
```

Guarda `parti/testata.php` della lezione (cartella `lezione/sito/parti`): fa già questo.

**Proteggere una pagina.**

```php
<?php
require __DIR__ . '/db.php';
richiediLogin();
// da qui in poi solo per chi è loggato
```

### Passi

1. Copia i tre file dalla cartella `lezione/sito` nella cartella `catalogo`.
2. Nei tre file cambia `require __DIR__ . '/funzioni.php';` in `require __DIR__ . '/db.php';`. Nel catalogo le funzioni stanno in `db.php`.
3. In `login.php` il redirect dopo il login porta a `riservata.php`, che nel catalogo non esiste. Cambialo con una pagina del catalogo.
4. Apri la testata del catalogo e quella della lezione, una accanto all'altra. Copia nella testata del catalogo la parte con `if (utenteCorrente())`. Tieni i link del catalogo (Catalogo, Autori, Preferiti...). Decidi quali link vedere solo da loggati: almeno "Nuovo libro" e "Nuovo autore".
5. Registrati con un utente di prova. Fai il login. Controlla che la testata cambi.
6. Aggiungi `richiediLogin()` nelle quattro pagine del punto 2.
7. In `libro.php` metti i link Modifica ed Elimina dentro un `if`.
8. Fai il logout e prova ad aprire `nuovo.php` scrivendo l'indirizzo.

### Trappole

- Se dimentichi il passo 2: errore `Failed to open stream ... funzioni.php`.
- Se dimentichi il passo 3: dopo il login arrivi a una pagina "Not Found".
- Il pulsante "Esci" è un form POST con il token, non un link. Copialo dalla testata della lezione così com'è.
- Nascondere i link Modifica ed Elimina **non basta** per proteggere: chi conosce l'indirizzo può scriverlo a mano. Per questo serve anche `richiediLogin()` nelle pagine.
- `richiediLogin()` va prima di qualsiasi stampa e prima del blocco POST.

### Domanda del punto 4: dove guardare

Leggi in `db.php` cosa fa la funzione `richiediLogin()` quando `utenteCorrente()` è `null`.

### Controllo

- Da non loggato: nella testata vedi Accedi e Registrati.
- Da loggato: vedi il tuo nome ed Esci.
- Da non loggato, nel dettaglio del libro non vedi Modifica ed Elimina.
- Da non loggato, `modifica.php?id=8` scritto a mano non mostra il form.
- In HeidiSQL, nella tabella `utenti`, la colonna `password_hash` contiene una stringa lunga che comincia con `$2y$`. Non contiene la tua password.
- Login con password sbagliata: messaggio di errore, nessun accesso.

---

## 7.3 – Token CSRF in tutti i form POST (30 minuti)

### La richiesta

1. In ogni form con `method="post"` aggiungi `<?= csrfCampo() ?>`.
2. In ogni pagina che riceve un POST, `csrfVerifica()` come prima istruzione del blocco POST.
3. Verifica: con F12 cancella il campo nascosto `csrf` da un form e invialo. Che codice ricevi?

### Cosa ti serve

**Cos'è il CSRF.**
CSRF vuol dire Cross-Site Request Forgery: richiesta falsificata da un altro sito.
Un altro sito può contenere un form che invia dati al **tuo** sito.
Il browser allega da solo il cookie di sessione.
Se l'utente è loggato da te, la richiesta parte a suo nome.

**La difesa.**
- `csrfCampo()` mette nel form un campo nascosto con un codice casuale, il **token**. Il token è salvato anche in sessione.
- `csrfVerifica()` controlla che il token arrivato con il POST sia uguale a quello in sessione.
- Un altro sito non conosce il token: la sua richiesta viene rifiutata.

Guarda `lezione/sito/csrf.php`: ha un form con il token e uno senza.

**La forma, in due punti.**

Nel form:

```php
<form method="post">
    <?= csrfCampo() ?>
    ...
</form>
```

Nel blocco POST:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    // ...
}
```

### Passi

1. Fai l'elenco di tutti i form con `method="post"` del catalogo. Cerca in VS Code: **Ctrl+Shift+F** e scrivi `method="post"`.
2. Fai l'elenco di tutte le pagine che ricevono un POST. Cerca `REQUEST_METHOD`.
3. Aggiungi prima `csrfCampo()` in **tutti** i form.
4. Poi aggiungi `csrfVerifica()` in **tutte** le pagine dell'elenco 2.
5. Prova ogni form: deve funzionare come prima.
6. Fai la prova del punto 3.

### Trappole

- Se metti `csrfVerifica()` in una pagina ma dimentichi `csrfCampo()` nel form che la chiama, quel form smette di funzionare: codice 419.
- Ci sono form in posti diversi dalla pagina che li riceve: il form di eliminazione sta in `libro.php` ma il POST arriva a `elimina.php`. Il form dei preferiti della 7.1 dipende da come l'hai fatto.
- I file copiati dalla lezione nella 7.2 hanno già il token. Controlla, ma non aggiungerlo due volte.

### Come fare la prova del punto 3

1. Apri una pagina con un form, per esempio `nuovo.php`.
2. Premi **F12**, scheda **Elements** (Elementi).
3. Trova il `<input type="hidden" name="csrf" ...>`.
4. Clic destro sulla riga > **Delete element** (Elimina elemento).
5. Invia il form.
6. Per vedere il codice: scheda **Network** (Rete), guarda la colonna Stato (Status) della richiesta.

### Controllo

- Tutti i form funzionano normalmente.
- Il form senza campo `csrf` risponde con il messaggio "Richiesta non valida" e il codice che leggi nella scheda Network.
- Con **Ctrl+U** su una pagina con un form vedi il campo nascosto con un codice lungo.

---

## 7.4 – Messaggi flash (30 minuti)

### La richiesta

Dopo un salvataggio la pagina dice "Libro salvato." una volta sola.

1. Prima del redirect: `$_SESSION['flash'] = 'Libro salvato.';`.
2. Nella testata: se `$_SESSION['flash']` esiste, mostralo in un paragrafo e poi cancellalo con `unset($_SESSION['flash'])`.
3. Usalo dopo nuovo libro, modifica, eliminazione e nuovo autore.

### Cosa ti serve

**Cos'è un messaggio flash.**
È un messaggio che si vede **una volta sola**, nella pagina successiva a un'azione.

Il problema: dopo il salvataggio c'è il redirect.
Il redirect apre una pagina nuova, con una richiesta nuova.
Le variabili della pagina di prima non esistono più.
La sessione invece resta: il messaggio si passa con la sessione.

**Il ciclo di vita del messaggio.**

1. Pagina A salva i dati, scrive il messaggio in sessione, fa il redirect.
2. Pagina B include la testata. La testata trova il messaggio, lo stampa e lo cancella dalla sessione.
3. Se ricarichi la pagina B, il messaggio non c'è più.

**La forma, sul sito del negozio.**

```php
// nella pagina che salva
$_SESSION['flash'] = 'Prodotto aggiunto al carrello.';
redirect('carrello.php');
```

```php
<!-- nella testata -->
<?php if (isset($_SESSION['flash'])) { ?>
    <p class="flash"><?= e($_SESSION['flash']) ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php } ?>
```

### Passi

1. Aggiungi il blocco del messaggio nella testata, subito dopo `<main>` o subito prima.
2. In `nuovo.php` scrivi il messaggio prima del redirect. Prova.
3. Fai lo stesso in `modifica.php`, `elimina.php`, `nuovo-autore.php`, con un testo adatto a ogni azione.
4. Se vuoi, aggiungi uno stile per la classe del paragrafo in `stile.css`.

### Trappole

- Il messaggio va scritto **prima** di `redirect(...)` o di `header(...)`: dopo, lo script è già finito.
- Se dimentichi `unset`, il messaggio compare in tutte le pagine successive.
- Il messaggio può contenere un titolo scritto dall'utente (es. "Libro *titolo* salvato"). Stampalo con `e()`.

### Controllo

- Salvi un libro nuovo: nel dettaglio compare il messaggio.
- Ricarichi la pagina (F5): il messaggio non c'è più.
- Vai in un'altra pagina: il messaggio non c'è.
- Il messaggio compare anche dopo modifica, eliminazione e nuovo autore.

---

## 7.5 – La copertina nel catalogo (40 minuti)

### La richiesta

1. Nel dettaglio del libro, per chi è loggato, un form per caricare la copertina, con le quattro regole della lezione (`upload.php`).
   Il file va in `uploads/`, il nome nella colonna `libri.copertina`.
2. Il dettaglio mostra la copertina, se c'è.
3. Prova a caricare un file di testo rinominato in `.jpg`: quale controllo lo blocca?

### Cosa ti serve

Il modello è `lezione/sito/upload.php`.
Leggilo tutto prima di iniziare.

**Le quattro regole della lezione.**

1. Controllare l'esito del caricamento.
2. Controllare la dimensione.
3. Controllare il tipo del file dal suo **contenuto**, non dal nome.
4. Dare al file un nome nuovo e casuale, mai quello scelto dall'utente.

**Il form per un file.**
Un form che invia un file ha bisogno di due cose:

- `method="post"`;
- `enctype="multipart/form-data"`. Senza, il file non arriva.

**`$_FILES`.**
I file caricati non stanno in `$_POST` ma in `$_FILES`.
`$_FILES['nome_del_campo']` è un array con queste chiavi:

| Chiave | Contenuto |
|---|---|
| `name` | il nome del file sul computer dell'utente |
| `tmp_name` | dove PHP ha messo il file, in una cartella temporanea |
| `size` | la dimensione in byte |
| `error` | `UPLOAD_ERR_OK` se è andato tutto bene, un altro valore se no |

**Mostrare l'immagine.**
Nel database c'è solo il nome del file.
L'immagine si mostra con un tag `<img>` che punta alla cartella:

```php
<?php if ($prodotto['foto']) { ?>
    <img src="uploads/<?= e($prodotto['foto']) ?>" alt="<?= e($prodotto['nome']) ?>">
<?php } ?>
```

### Passi

1. Controlla che nella cartella `catalogo` ci sia la sottocartella `uploads`.
2. Decidi chi riceve il POST del form: `libro.php` stesso oppure un file a parte. Nella lezione la pagina che riceve è la stessa del form.
3. Scrivi il form in `libro.php`, dentro l'`if` di chi è loggato. L'id del libro è già noto: non serve la `<select>` della lezione.
4. Copia dalla lezione il blocco dei controlli. Adatta l'id del libro.
5. Proteggi il POST: login e token CSRF.
6. Dopo l'UPDATE: messaggio flash e redirect al dettaglio dello stesso libro.
7. Aggiungi il tag `<img>` nel dettaglio.
8. Fai le prove del Controllo, poi la prova del punto 3.

### Trappole

- Se compare `Class "finfo" not found`, l'estensione `fileinfo` non è attiva. Nel `php.ini` (in `C:\php-8.3.36`) togli il `;` davanti a `extension=fileinfo` e riavvia il server.
- Se il blocco POST è in `libro.php`, deve stare **prima** dell'inclusione della testata e prima della stampa del libro.
- Se il libro di `libro.php` viene letto prima del POST, dopo l'upload devi fare il redirect, altrimenti vedi ancora la copertina vecchia.
- `move_uploaded_file` vuole il percorso completo della cartella: usa `__DIR__ . '/uploads/'`.
- Con un file più grande dei limiti di PHP (`upload_max_filesize` nel `php.ini`, di solito 2 MB) non arriva la regola 2 ma la regola 1: il campo `error` non è `UPLOAD_ERR_OK`.
- Il tipo dichiarato dal browser (`$_FILES[...]['type']`) lo decide l'utente. La lezione non lo usa. Non usarlo nemmeno tu.

### Domanda del punto 3: dove guardare

Rileggi le quattro regole.
Quale delle quattro guarda dentro il file?
Il messaggio di errore che ottieni contiene anche il tipo riconosciuto: leggilo.

### Controllo

- Da non loggato il form non si vede.
- Carichi un'immagine JPG o PNG: il dettaglio la mostra.
- In HeidiSQL la colonna `copertina` del libro contiene un nome lungo e casuale, non il nome originale del file.
- Nella cartella `uploads` c'è il file con quel nome.
- Senza scegliere un file: messaggio "Nessun file scelto".
- File PDF: messaggio di tipo non ammesso.

---

## 7.6 – Caccia alle vulnerabilità (20 minuti)

### La richiesta

Apri `vulnerabile.php` e, **senza eseguirlo**, scrivi in un commento in cima al file: quali problemi ha, quale attacco permette ciascuno e come si corregge.
Poi correggilo.

### Come scrivere il commento

In PHP un commento su più righe si scrive così:

```php
<?php
/*
 * Problema 1: ...
 *   Attacco: ...
 *   Correzione: ...
 */
```

### Come cercare i problemi

Il commento in cima al file dice che i problemi sono almeno tre.

Leggi il file una riga alla volta.
Per ogni riga che usa un valore, chiediti:

1. Da dove arriva questo valore? Lo scrive l'utente (`$_GET`, `$_POST`) o il programma?
2. Se finisce in una query: come ci entra? Rileggi la regola del giorno 6.
3. Se finisce nella pagina HTML: come viene stampato? Rileggi la regola del giorno 6.
4. Se la pagina riceve un POST che modifica i dati: chi può inviare quel form? Rileggi la 7.2 e la 7.3.

Per ogni attacco, rileggi il file della lezione che lo mostra (cartella `lezione/sito`, file del capitolo 04) e il suo commento in cima.

Per la correzione puoi copiare la forma delle pagine che hai già sistemato oggi, per esempio `modifica.php`.

### Trappole

- "Senza eseguirlo" vuol dire: prima trova i problemi solo leggendo. La pagina modifica i dati del database.
- Un problema può comparire in più righe dello stesso file. Correggile tutte.
- Dopo la correzione la pagina deve fare la stessa cosa di prima: rinominare un libro.

### Controllo

- Il commento elenca almeno tre problemi, ognuno con attacco e correzione.
- Nel file corretto non c'è nessuna variabile scritta dentro una stringa SQL.
- Nel file corretto ogni valore stampato nell'HTML passa da `e()`.
- Dopo la correzione, apri la pagina con l'id di un libro e rinomina il libro: funziona.
- Prova a rinominare un libro con un titolo che contiene un apice, per esempio `L'isola`: deve funzionare senza errori SQL.

---

## Errori frequenti

| Messaggio o sintomo | Causa probabile |
|---|---|
| `Duplicate column name 'copertina'` | Hai eseguito `00-utenti.sql` due volte. La colonna c'è già: puoi andare avanti. |
| `Table 'biblioteca.utenti' doesn't exist` | Non hai eseguito `00-utenti.sql`, oppure hai rieseguito i file `00` del giorno 5 dopo. |
| `Failed to open stream ... funzioni.php` | Nei file copiati dalla lezione c'è ancora `funzioni.php`. Nel catalogo si chiama `db.php`. |
| `Cannot modify header information - headers already sent` | È stato stampato qualcosa prima di `redirect()` o di `session_start()`. Spesso: uno spazio o una riga vuota prima di `<?php`, oppure la testata inclusa prima del blocco POST. |
| `session_start(): Session cannot be started after headers have already been sent` | Come sopra: qualcosa è stato stampato prima di `require 'db.php'`. |
| `Richiesta non valida (token CSRF mancante o sbagliato)` (codice 419) | Il form non ha `csrfCampo()`, oppure la sessione è scaduta o è stata svuotata. Ricarica la pagina del form e riprova. |
| Dopo il login la pagina è "Not Found" | Il redirect di `login.php` punta ancora a `riservata.php`. |
| Il login non riesce mai | L'utente è stato registrato prima di rieseguire il database, oppure password sbagliata. Registrati di nuovo. |
| La testata non cambia dopo il login | La testata non usa `utenteCorrente()`, oppure stai guardando un'altra finestra (incognito). |
| `Warning: Undefined array key "preferiti"` | Leggi `$_SESSION['preferiti']` prima che esista. Usa `?? []`. |
| `count(): Argument #1 must be of type Countable\|array, null given` | Come sopra: `count` su una chiave che non esiste. |
| Il messaggio flash compare sempre | Manca `unset($_SESSION['flash'])`. |
| Il messaggio flash non compare mai | È scritto dopo il redirect, oppure la testata usa una chiave diversa. |
| `Class "finfo" not found` | Estensione `fileinfo` non attiva nel `php.ini`. |
| `$_FILES` è vuoto | Manca `enctype="multipart/form-data"` nel form. |
| `move_uploaded_file(...): Failed to open stream` | La cartella `uploads` non esiste, o il percorso è sbagliato. |
| L'immagine non si vede (icona rotta) | Il percorso in `src` è sbagliato, oppure il nome nel database non corrisponde al file in `uploads`. |
