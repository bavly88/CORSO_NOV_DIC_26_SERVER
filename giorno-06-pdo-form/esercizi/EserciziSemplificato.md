# Giorno 6 – Esercizi del pomeriggio (PHP e database) – versione spiegata

Oggi colleghi le pagine PHP al database `biblioteca`.
Lavori sul catalogo della lezione e aggiungi nuove pagine: autori, nuovo autore, modifica ed eliminazione dei libri.

Ogni esercizio ha questa forma:

- la richiesta, con il nome del file da creare;
- una spiegazione di cosa serve, con un esempio;
- i passi da seguire uno alla volta;
- le **trappole**, cioè gli errori più comuni;
- il **Controllo**, cioè come capire se la pagina funziona.

> **Importante: gli esempi mostrano la forma, non sono la soluzione.**
> Gli esempi usano un database diverso, inventato: un'azienda con le tabelle `dipendenti` e `reparti`.
> Devi adattare la forma alle tabelle della biblioteca.

---

## Prima di iniziare

### 1. Prepara la cartella

1. Copia la cartella `esercizi/partenza`.
2. Rinomina la copia in `catalogo`.

Dentro trovi il catalogo della lezione:

| File | Cosa fa |
|---|---|
| `config.php` | i dati per collegarsi al database (utente e password `corso`) |
| `db.php` | la funzione `db()`, che restituisce la connessione, e la funzione `e()` |
| `index.php` | l'elenco dei libri con la ricerca |
| `libro.php` | il dettaglio di un libro: `libro.php?id=8` |
| `nuovo.php` | il form per inserire un libro |
| `parti/testata.php` e `parti/piede.php` | la parte alta e la parte bassa di ogni pagina |
| `stile.css` | lo stile |

Leggi `libro.php` e `nuovo.php` prima di iniziare.
Gli esercizi di oggi copiano la loro forma.

### 2. Controlla il database

Il database è `biblioteca` del giorno 5.
Se l'hai modificato troppo, riesegui in HeidiSQL i due file `00-schema-biblioteca.sql` e `00-dati-biblioteca.sql` (F9 per eseguire tutto).
Il primo file cancella e ricrea il database: tutte le modifiche precedenti vanno perse.

Tieni HeidiSQL aperto: ti serve per guardare le tabelle e per provare le query prima di metterle in PHP.

### 3. Avvia il server

Apri il terminale di VS Code (Git Bash) **nella cartella `catalogo`** e scrivi:

```bash
php -S localhost:8000
```

Poi apri il browser su `http://localhost:8000`.
Il terminale deve restare aperto finché lavori.
Per fermare il server: **Ctrl+C** nel terminale.

Nel terminale compare una riga per ogni pagina richiesta.
Gli errori PHP compaiono lì e nella pagina.

### 4. Il metodo di lavoro

Per ogni query:

1. Scrivila e provala in HeidiSQL, con un numero al posto del `?`.
2. Quando funziona, copiala in PHP e metti `?` al posto del numero.

Per ogni pagina:

1. Salva il file.
2. Ricarica la pagina nel browser (F5).
3. Se vedi un errore, leggilo tutto: dice il file e la riga.

---

## La regola di oggi

Ogni valore che arriva da `$_GET` o `$_POST` entra nella query **solo** con `prepare` ed `execute`, e si stampa **solo** con `e()`.

Cosa vuol dire:

- `$_GET` contiene i valori dell'indirizzo. In `autore.php?id=3`, `$_GET['id']` vale `"3"`.
- `$_POST` contiene i campi di un form inviato con `method="post"`.
- Questi valori li scrive l'utente. Possono contenere qualsiasi cosa.

**Nella query: `prepare` ed `execute`.**
Nella query si scrive un **segnaposto** `?` al posto del valore.
Il valore si passa dopo, a `execute`.
Così il valore non può cambiare la query.

```php
$stmt = db()->prepare("SELECT nome, cognome FROM dipendenti WHERE id = ?");
$stmt->execute([$id]);
$dipendente = $stmt->fetch();
```

Mai così:

```php
db()->query("SELECT * FROM dipendenti WHERE id = $id");   // SBAGLIATO
```

**Nella pagina: `e()`.**
`e()` è definita in `db.php`.
Trasforma i caratteri speciali dell'HTML (`<`, `>`, `"`, `&`) in testo normale.
Così un testo come `<b>ciao</b>` si vede scritto, invece di diventare grassetto.

```php
<p><?= e($dipendente['nome']) ?></p>
```

### Promemoria PDO

| Istruzione | Cosa fa |
|---|---|
| `db()->query("...")` | esegue una query **senza** valori dall'esterno |
| `db()->prepare("... ? ...")` | prepara una query con segnaposto |
| `$stmt->execute([valore1, valore2])` | esegue la query preparata con i valori, nell'ordine dei `?` |
| `$stmt->fetch()` | restituisce **una** riga come array, oppure `false` se non c'è nessuna riga |
| `$stmt->fetchAll()` | restituisce **tutte** le righe come array di array |
| `db()->lastInsertId()` | restituisce l'id dell'ultima riga inserita con INSERT |

---

## 6.1 – La pagina degli autori (40 minuti) – `autori.php`

### La richiesta

1. Elenco degli autori in ordine di cognome, con il numero dei loro libri (JOIN con `libri_autori` e `GROUP BY`).
   Devono comparire anche gli autori senza libri, con 0 (`LEFT JOIN` e `COUNT(la.libro_id)`).
2. Ogni nome è un link a `autore.php?id=...`.
3. Aggiungi il link "Autori" nella testata.

### Cosa ti serve

**La query.**
È lo stesso ragionamento della 5.13 del giorno 5: LEFT JOIN, conteggio di una colonna della tabella di destra, GROUP BY.
Rileggi la tua soluzione della 5.13.

Questa pagina non riceve valori dall'esterno.
Puoi usare `db()->query(...)` senza `prepare`.

```php
$reparti = db()->query(
    "SELECT r.id, r.nome, COUNT(d.id) AS quanti
     FROM reparti r
     LEFT JOIN dipendenti d ON d.reparto_id = r.id
     GROUP BY r.id, r.nome
     ORDER BY r.nome"
)->fetchAll();
```

**Il link con l'id.**
Ogni riga dell'elenco ha il suo id.
Lo scrivi nell'indirizzo del link:

```php
<?php foreach ($reparti as $r) { ?>
    <li>
        <a href="reparto.php?id=<?= $r['id'] ?>"><?= e($r['nome']) ?></a>
        (<?= $r['quanti'] ?>)
    </li>
<?php } ?>
```

**La forma della pagina.**
Guarda `index.php`: prima la query, poi `$titoloPagina`, poi `require` della testata, poi l'HTML, poi `require` del piede.

### Passi

1. Scrivi la query in HeidiSQL. Controlla il risultato.
2. Crea `autori.php` copiando la struttura di `index.php`. Togli la parte della ricerca.
3. Incolla la query. Stampa l'elenco con un `foreach`.
4. Apri `http://localhost:8000/autori.php`.
5. Aggiungi il link "Autori" in `parti/testata.php`, accanto agli altri link.

### Trappole

- `COUNT(*)` invece di `COUNT(la.libro_id)`: l'autore senza libri ha 1 invece di 0.
- Il link porta a `autore.php` (al singolare), che non esiste ancora: lo crei nella 6.2. Per ora cliccando vedi un errore 404. È normale.
- Nome e cognome stanno in due colonne. Per stamparli insieme: `e($a['nome'] . ' ' . $a['cognome'])`.

### Controllo

- La pagina mostra 10 autori.
- Un autore ha 0 libri.
- I cognomi sono in ordine alfabetico.
- Il link "Autori" compare in tutte le pagine, perché la testata è comune.

---

## 6.2 – La pagina di un autore (40 minuti) – `autore.php`

### La richiesta

1. Legge l'autore con l'id dell'indirizzo; se non esiste, 404.
2. Mostra nome, cognome, nazionalità, anno di nascita.
3. Sotto, l'elenco dei suoi libri, con il link al dettaglio.
4. Nel dettaglio del libro (`libro.php`) i nomi degli autori diventano link alla loro pagina.

### Cosa ti serve

Il modello è `libro.php`.
Leggilo riga per riga: fa esattamente le stesse cose, per un libro.

**Leggere l'id dall'indirizzo.**

```php
$id = (int) ($_GET['id'] ?? 0);
```

- `$_GET['id'] ?? 0`: se nell'indirizzo non c'è `id`, usa 0.
- `(int)`: trasforma il valore in numero intero. Se l'utente scrive `?id=abc`, diventa 0.

**Il 404.**
404 è il codice HTTP che vuol dire "pagina non trovata".
`fetch()` restituisce `false` se la query non trova righe.
In quel caso si manda il codice 404 e si ferma la pagina con `exit`.
Guarda il blocco `if ($libro === false)` in `libro.php`.

**Due query nella stessa pagina.**
1. La prima legge l'autore.
2. La seconda legge i suoi libri, passando per `libri_autori` (relazione molti a molti, come nella 5.14).

Esempio di seconda query, nel database dell'azienda:

```php
$stmt = db()->prepare(
    "SELECT d.id, d.nome, d.cognome
     FROM dipendenti d
     WHERE d.reparto_id = ?
     ORDER BY d.cognome"
);
$stmt->execute([$id]);
$dipendenti = $stmt->fetchAll();
```

**Valori mancanti.**
Nazionalità e anno di nascita possono essere `NULL`.
`libro.php` usa `?? '-'` per stampare un trattino al posto del valore mancante.

### Passi

1. Crea `autore.php` copiando `libro.php`.
2. Cambia la prima query: legge dalla tabella degli autori.
3. Cambia il blocco del 404: il testo deve parlare di autore.
4. Cambia la seconda query: legge i libri dell'autore. Provala prima in HeidiSQL con un id al posto del `?`.
5. Cambia l'HTML: dati dell'autore sopra, elenco dei libri sotto, ogni titolo con il link `libro.php?id=...`.
6. Apri `libro.php`. Nella query degli autori aggiungi l'id dell'autore. Trasforma ogni nome in un link a `autore.php?id=...`.

### Trappole

- Nella query dei libri il `?` deve essere confrontato con la colonna dell'autore nella tabella ponte, non con l'id del libro.
- Al passo 6, se non aggiungi l'id nella SELECT, `$a['id']` non esiste: errore "Undefined array key".
- Se togli `exit` dopo il 404, la pagina continua e dà altri errori.

### Controllo

- `autore.php?id=1` mostra Italo Calvino e 3 libri.
- `autore.php?id=10` mostra un autore senza libri: l'elenco è vuoto, ma la pagina funziona.
- `autore.php?id=999` mostra "non trovato".
- `autore.php?id=abc` mostra "non trovato".
- Per controllare il codice 404: premi F12, scheda **Rete** (Network), ricarica. La riga della pagina deve avere stato 404.
- Da "La donna della domenica" (due autori) puoi andare alla pagina di entrambi gli autori.

---

## 6.3 – Nuovo autore (50 minuti) – `nuovo-autore.php`

### La richiesta

Sul modello di `nuovo.php`:

1. Campi: nome, cognome (obbligatori, al massimo 60 caratteri), nazionalità (facoltativa), anno di nascita (facoltativo, tra 1000 e l'anno corrente).
2. Errori accanto ai campi e valori inseriti mantenuti dopo un errore.
3. Se è tutto valido: INSERT e redirect alla pagina del nuovo autore.
4. Prova a scrivere `<b>Mario</b>` come nome: come compare nella pagina dell'autore?

### Cosa ti serve

**Una pagina, due comportamenti.**
`nuovo.php` fa due cose diverse a seconda di come viene richiesta:

- **GET**: apri la pagina dal link. Mostra il form vuoto.
- **POST**: premi "Salva". Controlla i dati. Se ci sono errori, mostra di nuovo il form. Se è tutto giusto, salva.

Il controllo è:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // qui si leggono e si controllano i dati del form
}
```

**Le parti di `nuovo.php` da riconoscere.**

| Parte | A cosa serve |
|---|---|
| `$errori = []` | qui si scrivono i messaggi di errore, uno per campo |
| `$dati = [...]` | i valori inseriti, per rimetterli nel form dopo un errore |
| `trim($_POST['...'] ?? '')` | legge un campo e toglie gli spazi all'inizio e alla fine |
| i blocchi `if (...) { $errori[...] = ... }` | la **validazione**: il controllo dei dati |
| `if (count($errori) === 0)` | se non ci sono errori, salva |
| `header('Location: ...'); exit;` | il **redirect**: manda il browser a un'altra pagina |
| `value="<?= e($dati['...']) ?>"` | rimette nel campo il valore inserito |
| `<?php if (isset($errori['...'])) ...` | stampa l'errore accanto al campo |

**Le funzioni per validare.**

| Funzione | Cosa fa |
|---|---|
| `mb_strlen($testo)` | conta i caratteri, anche quelli accentati |
| `ctype_digit($testo)` | `true` se il testo contiene solo cifre |
| `date('Y')` | l'anno corrente, come testo |

Esempio di validazione su un campo obbligatorio e uno facoltativo, per un dipendente:

```php
if ($dati['cognome'] === '') {
    $errori['cognome'] = 'Il cognome è obbligatorio.';
} elseif (mb_strlen($dati['cognome']) > 60) {
    $errori['cognome'] = 'Al massimo 60 caratteri.';
}

// facoltativo: si controlla solo se è stato scritto qualcosa
if ($dati['stipendio'] !== '' && !ctype_digit($dati['stipendio'])) {
    $errori['stipendio'] = 'Lo stipendio deve essere un numero.';
}
```

**Campo facoltativo vuoto → NULL.**
Se un campo facoltativo è vuoto, nel database va `NULL`, non un testo vuoto.
Guarda in `nuovo.php` come si fa per l'anno: `$dati['anno'] === '' ? null : (int) $dati['anno']`.

**Il redirect alla pagina nuova.**
Dopo l'INSERT, `db()->lastInsertId()` dà l'id del nuovo autore.
Lo usi per costruire l'indirizzo di `autore.php`.

**Perché il redirect.**
Si chiama Post/Redirect/Get.
Senza redirect, se l'utente ricarica la pagina il browser rimanda il form e l'autore viene salvato due volte.

### Passi

1. Copia `nuovo.php` in `nuovo-autore.php`.
2. Togli la query dei generi e il campo del genere: non servono.
3. Cambia `$dati`: le chiavi devono essere i quattro campi dell'autore.
4. Cambia i campi del form. L'attributo `name` di ogni campo deve essere uguale alla chiave in `$dati`.
5. Scrivi la validazione, un campo alla volta. Prova ogni campo prima di passare al successivo.
6. Cambia l'INSERT: tabella degli autori, quattro colonne, quattro `?`.
7. Cambia il redirect.
8. Aggiungi nella testata il link "Nuovo autore".
9. Fai la prova del punto 4.

### Trappole

- Il `name` nel form e la chiave in `$_POST` devono essere scritti uguali. Se sono diversi, il campo arriva vuoto.
- Il numero di `?` nell'INSERT deve essere uguale al numero di valori in `execute`. Altrimenti: errore `SQLSTATE[HY093]`.
- Prima di `header('Location: ...')` non deve essere stampato niente. Se la testata è già stata inclusa, compare l'avviso "Cannot modify header information - headers already sent". Il `require` della testata va **dopo** il blocco POST, come in `nuovo.php`.
- Dopo `header(...)` serve `exit`. Senza, la pagina continua.
- Nella validazione dell'anno: prima controlla che siano solo cifre, poi confronta con 1000 e con l'anno corrente.

### Domanda del punto 4: dove guardare

- Guarda la pagina dell'autore nel browser.
- Poi premi **Ctrl+U** per vedere il codice HTML che il server ha mandato. Cerca il nome.
- Rileggi cosa fa `e()` nella sezione "La regola di oggi".

### Controllo

- Form inviato vuoto: due errori, accanto a nome e cognome.
- Cognome di 61 caratteri: errore.
- Anno 999, anno dell'anno prossimo, anno `abc`: errore.
- Dopo un errore, i campi giusti contengono ancora quello che avevi scritto.
- Dati giusti: arrivi alla pagina del nuovo autore.
- In HeidiSQL (F5 sulla tabella degli autori) il nuovo autore c'è. Se hai lasciato vuota la nazionalità, la cella è `(NULL)`, non vuota.
- Nella pagina `autori.php` il nuovo autore compare con 0 libri.

---

## 6.4 – Modificare un libro (50 minuti) – `modifica.php`

### La richiesta

1. `modifica.php?id=8`: il form di `nuovo.php` già compilato con i dati del libro (con GET si legge il libro dal database, con POST si salvano le modifiche).
2. Stessa validazione di `nuovo.php`.
3. Se valido: `UPDATE libri SET ... WHERE id = ?` e redirect al dettaglio.
4. Nel dettaglio aggiungi il link "Modifica".

### Cosa ti serve

**La differenza con `nuovo.php`.**

| | `nuovo.php` | `modifica.php` |
|---|---|---|
| Apertura (GET) | form vuoto | form con i dati letti dal database |
| Salvataggio (POST) | `INSERT` | `UPDATE ... WHERE id = ?` |
| Dopo il salvataggio | redirect al libro nuovo | redirect allo stesso libro |
| Serve l'id? | no | sì, dall'indirizzo |

**Riempire il form con GET.**
All'apertura leggi il libro con l'id dell'indirizzo, come in `libro.php`.
Poi copi i valori del libro in `$dati`.
Il form usa già `$dati` per i `value`: si riempie da solo.

**L'id durante il POST.**
Un form con `method="post"` e senza `action` viene inviato allo **stesso indirizzo**, compreso `?id=8`.
Quindi anche durante il POST l'id si legge da `$_GET['id']`.

**La sintassi dell'UPDATE.**

```php
$stmt = db()->prepare(
    "UPDATE dipendenti SET nome = ?, cognome = ?, stipendio = ? WHERE id = ?"
);
$stmt->execute([$dati['nome'], $dati['cognome'], $stipendio, $id]);
```

L'id va per ultimo in `execute`, perché il suo `?` è l'ultimo nella query.

### Passi

1. Copia `nuovo.php` in `modifica.php`.
2. All'inizio leggi l'id e il libro, come in `libro.php`. Se il libro non esiste: 404.
3. Riempi `$dati` con i valori del libro. Apri `modifica.php?id=8`: il form deve essere compilato.
4. Nel blocco POST sostituisci l'INSERT con l'UPDATE.
5. Cambia il redirect: torna a `libro.php` con lo stesso id.
6. Cambia il titolo della pagina e il testo del pulsante.
7. In `libro.php` aggiungi il link `modifica.php?id=...`.

### Trappole

- Un UPDATE senza `WHERE id = ?` modifica **tutti** i libri. Prova la query in HeidiSQL prima, con una SELECT con lo stesso WHERE.
- I valori `NULL` del database (per esempio le pagine del libro 16) devono diventare testo vuoto in `$dati`, altrimenti `e()` e `trim()` possono comportarsi in modo inatteso. `?? ''` trasforma `NULL` in testo vuoto.
- La `<select>` del genere: l'opzione giusta è già marcata con `selected` dal codice di `nuovo.php`, ma solo se `$dati['genere_id']` contiene il genere del libro.
- Il form ha 4 campi, la tabella ha più colonne. Nell'UPDATE metti solo le colonne del form. Le altre (per esempio l'editore) restano come sono.

### Controllo

- `modifica.php?id=8` mostra "Il nome della rosa" con anno, pagine e genere giusti.
- Cambi le pagine e salvi: arrivi al dettaglio con il nuovo numero.
- Con titolo vuoto: errore, e il libro non cambia.
- In HeidiSQL controlla che sia cambiato **solo** quel libro.
- `modifica.php?id=999`: "non trovato".
- `modifica.php?id=16`: il form si apre anche se il libro non ha pagine.

---

## 6.5 – Eliminare un libro (30 minuti) – `elimina.php`

### La richiesta

1. Nel dettaglio del libro, un form con `method="post"` e `action="elimina.php"`, un campo nascosto con l'id e un pulsante "Elimina" (con `onsubmit="return confirm('Eliminare il libro?')"`).
2. `elimina.php` accetta solo POST, esegue il DELETE e torna al catalogo.
3. Prova a eliminare "Il nome della rosa": il database lo rifiuta, perché ha dei prestiti.
   Intercetta l'errore con `try / catch (PDOException $e)` e mostra un messaggio leggibile invece della pagina di errore.

### Cosa ti serve

**Perché un form e non un link.**
Un link fa una richiesta GET.
Le richieste GET non devono modificare i dati: i browser e i motori di ricerca possono aprirle da soli.
Per cancellare si usa POST.

**Il campo nascosto.**
Un campo `type="hidden"` non si vede nella pagina, ma viene inviato con il form.

```html
<form method="post" action="elimina-dipendente.php">
    <input type="hidden" name="id" value="7">
    <button type="submit">Elimina</button>
</form>
```

**`confirm()`.**
`onsubmit="return confirm('...')"` mostra una finestra con OK e Annulla.
Se l'utente preme Annulla, il form non viene inviato.

**Accettare solo POST.**
All'inizio di `elimina.php` controlli il metodo.
Se non è POST, rispondi con il codice 405 ("metodo non permesso") e fermi la pagina.

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metodo non permesso');
}
```

**`try / catch`.**
La connessione è configurata in modo che un errore SQL diventi un'**eccezione** (guarda `PDO::ERRMODE_EXCEPTION` in `db.php`).
Un'eccezione ferma la pagina e mostra l'errore.
Con `try / catch` la intercetti e decidi tu cosa mostrare.

```php
try {
    $stmt = db()->prepare("DELETE FROM reparti WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    // qui arrivi solo se il DELETE ha dato errore
    // $e->getMessage() contiene il messaggio del database
}
```

### Passi

1. In `libro.php`, sotto il link "Modifica", aggiungi il form del punto 1.
2. Crea `elimina.php`. Metti il controllo sul metodo.
3. Leggi l'id da `$_POST` (non da `$_GET`) e trasformalo in intero.
4. Scrivi il DELETE con `prepare` ed `execute`. Dopo, redirect a `index.php`.
5. Prova con un libro **senza prestiti**. Il libro deve sparire dal catalogo.
6. Prova con "Il nome della rosa". Leggi l'errore che compare.
7. Metti il DELETE dentro `try`. Nel `catch` mostra una pagina con un messaggio in italiano e un link per tornare al libro.

### Trappole

- Il `name` del campo nascosto deve corrispondere alla chiave che leggi in `$_POST`.
- Il redirect va **dentro** `try`, dopo `execute`. Se lo metti dopo tutto il blocco, viene eseguito anche quando c'è un errore.
- Per mostrare la pagina di errore con la testata, il `require` della testata va dentro il `catch`, perché prima non è stato stampato niente.
- La cancellazione è definitiva. Dopo le prove, riesegui i file `00` per riavere i dati originali.
- Aprendo `elimina.php` direttamente dalla barra degli indirizzi fai una richiesta GET: deve rispondere 405.

### Per capire l'errore del punto 3

Un libro senza prestiti si cancella, "Il nome della rosa" no.
Per capire perché, guarda nel file `00-schema-biblioteca.sql` le chiavi esterne che puntano alla tabella dei libri, e cosa c'è scritto dopo `ON DELETE`.
È lo stesso argomento della 5.21.

### Controllo

- Premendo "Elimina" compare la finestra di conferma. Con Annulla non succede niente.
- Un libro senza prestiti sparisce dal catalogo e da HeidiSQL.
- "Il nome della rosa" non sparisce, e vedi il tuo messaggio, non la pagina di errore di PHP.
- `http://localhost:8000/elimina.php` aperto dalla barra degli indirizzi: risposta 405.

---

## Errori frequenti

| Messaggio o sintomo | Causa probabile |
|---|---|
| `could not find driver` | L'estensione `pdo_mysql` non è attiva. Nel file `php.ini` (in `C:\php-8.3.36`) togli il `;` davanti a `extension=pdo_mysql`. Riavvia il server. Controlla con `php -m`. |
| `SQLSTATE[HY000] [2002] Impossibile stabilire la connessione` (o `Connection refused`) | MariaDB non è avviato. |
| `SQLSTATE[HY000] [1045] Access denied for user 'corso'` | Utente o password sbagliati in `config.php`. |
| `SQLSTATE[HY000] [1049] Unknown database 'biblioteca'` | Il database non esiste. Riesegui i file `00`. |
| `Failed to open stream: No such file or directory ... config.php` | `config.php` non è nella stessa cartella di `db.php`. |
| `Call to undefined function e()` o `db()` | Manca `require __DIR__ . '/db.php';` all'inizio del file. |
| `SQLSTATE[42S02] ... Table ... doesn't exist` | Nome della tabella sbagliato nella query. |
| `SQLSTATE[42S22] ... Unknown column` | Nome della colonna sbagliato. Controlla in HeidiSQL. |
| `SQLSTATE[42000] ... You have an error in your SQL syntax` | Errore di scrittura nella query. Copiala in HeidiSQL e provala lì. |
| `SQLSTATE[HY093] Invalid parameter number` | Il numero di `?` nella query è diverso dal numero di valori in `execute`. |
| `SQLSTATE[23000] ... Cannot delete or update a parent row` | Il libro ha dei prestiti. È l'errore atteso nella 6.5. |
| `Warning: Undefined array key "xxx"` | La chiave non esiste nell'array. Controlla il `name` del campo nel form, o la colonna nella SELECT. |
| `Cannot modify header information - headers already sent` | Prima di `header()` è stato stampato qualcosa: la testata, un `echo`, uno spazio prima di `<?php`. |
| La pagina dopo il salvataggio è bianca | Manca il redirect, oppure c'è un errore. Guarda il terminale del server. |
| `Not Found` nel browser | Il file non esiste nella cartella da cui hai avviato il server, oppure il nome è scritto diversamente. |
| Le modifiche al file non si vedono | Il file non è salvato, oppure il server è avviato da un'altra cartella. |
| Il form si invia ma non succede niente | Manca `method="post"`, oppure il pulsante non è dentro il `<form>`. |
| Ricaricando dopo il salvataggio, il browser chiede di "inviare di nuovo i dati" | Manca il redirect dopo l'INSERT o l'UPDATE. |
