# Giorno 4 – Esercizi del pomeriggio
## Database, tabelle e primi SELECT

---

## Prima di iniziare

**Strumento.** Lavora in HeidiSQL (o DBeaver). Collegati con l'utente `corso`.

**Dove scrivere le soluzioni.** Crea nella tua cartella un file per ogni esercizio, con il nome indicato nel titolo (`es-4-2.md`, `es-4-3.sql`, ...).
- I file `.sql` contengono comandi SQL: li apri in HeidiSQL e li esegui.
- I file `.md` contengono testo: risposte scritte, elenchi, schemi.

**Perché salvare in file.** Se sbagli qualcosa puoi cancellare le tabelle e rieseguire lo script dall'inizio, invece di riscrivere tutto a mano.

**Come eseguire uno script in HeidiSQL.**
1. Seleziona il database a sinistra (oppure scrivi `USE lezione_sql;` in cima al file).
2. Apri una scheda **Query** e incolla il codice, oppure usa *File → Carica file SQL*.
3. Premi **F9** per eseguire tutto, oppure **Ctrl+F9** per eseguire solo il testo selezionato.
4. Dopo un `CREATE` o un `INSERT` premi **F5** sull'albero a sinistra per aggiornarlo, altrimenti le nuove tabelle non compaiono.

**I commenti in SQL.** Una riga che inizia con `--` (due trattini e uno spazio) è un commento: il database la ignora. Usali per scrivere le tue risposte dentro i file `.sql`.

```sql
-- Questo è un commento
SELECT * FROM libri;   -- anche questo
```

---

## 4.1 – Gli script della lezione (15 minuti)

**Obiettivo:** rieseguire quello che abbiamo visto a lezione e capire come il database rifiuta i dati sbagliati.

### Passo 1 – Esegui gli script nell'ordine giusto
1. `02-crea-tabelle.sql` crea le tabelle.
2. `03-inserisci.sql` le riempie di dati.
3. `04-select.sql` fa le interrogazioni.

L'ordine conta: non puoi inserire dati in una tabella che non esiste ancora.

### Passo 2 – Guarda i dati
In HeidiSQL apri il database `lezione_sql` a sinistra e clicca su ogni tabella. Poi apri la scheda **Dati** (in alto): vedi le righe che sono state inserite.
Guarda anche la scheda **Struttura** (o *Tabella*): mostra le colonne, i tipi e le chiavi.

### Passo 3 – Le tre righe di errore
In fondo a `03-inserisci.sql` ci sono tre `INSERT` che il database rifiuta. Eseguile **una alla volta** (selezionale e premi Ctrl+F9) e leggi il messaggio di errore.

Ogni errore dipende da una di queste regole:

| Regola | Cosa vieta | Messaggio tipico in MariaDB |
|---|---|---|
| `NOT NULL` | lasciare vuota una colonna obbligatoria | `Column '...' cannot be null` |
| `UNIQUE` | inserire un valore già presente in quella colonna | `Duplicate entry '...' for key '...'` |
| `FOREIGN KEY` | fare riferimento a un id che non esiste nell'altra tabella | `Cannot add or update a child row: a foreign key constraint fails` |

**Cosa consegnare:** sopra ogni riga di errore scrivi un commento con la regola che l'ha fermata e il perché.

```sql
-- Errore: FOREIGN KEY – il genere con id 50 non esiste nella tabella generi
INSERT INTO ...
```

---

## 4.2 – Normalizzare su carta (30 minuti) – `es-4-2.md`

**Obiettivo:** capire perché i dati si dividono in più tabelle invece di tenerli in un unico foglio.

**Non si scrive SQL**, solo ragionamento.

La biblioteca teneva i prestiti in un foglio Excel:

| socio | email | titolo | autori | data prestito |
|---|---|---|---|---|
| Anna Rossi | anna@example.com | Il nome della rosa | Umberto Eco | 2026-10-02 |
| Anna Rossi | anna@example.com | La donna della domenica | Carlo Fruttero, Franco Lucentini | 2026-10-15 |
| Marco Bianchi | marco@example.com | Il nome della rosa | Umberto Eco | 2026-10-20 |

Rispondi alle domande:

**1. Elenca le informazioni scritte più di una volta.**
Scorri le colonne e cerca i valori che si ripetono in righe diverse. Per ognuno scrivi quante volte compare.
*Esempio di risposta:* "Il nome di Anna Rossi compare 2 volte."

**2. Quale cella contiene più valori?**
Cerca una cella che dentro ha due informazioni separate (per esempio da una virgola). In un database ogni cella deve contenere **un solo valore**. Spiega che problema crea quella cella, per esempio se vuoi cercare tutti i libri di Franco Lucentini.

**3. Cosa succede se Anna cambia email?**
Pensa a quante righe vanno modificate. Cosa succede se ne correggi una e ti dimentichi l'altra? Quale delle due email è quella giusta?

**4. Proponi le tabelle.**
Chiediti: *di quali "cose" parla questo foglio?* Ogni cosa diventa una tabella. Ci sono persone, libri, autori e prestiti.

Per ogni tabella scrivi:
- il **nome** (al plurale, minuscolo: `soci`, `libri`, ...);
- le **colonne**;
- la **chiave primaria** (PK): la colonna che identifica ogni riga in modo univoco, di solito `id`;
- le **chiavi esterne** (FK): le colonne che contengono l'id di una riga di un'altra tabella.

*Suggerimenti:*
- Un libro può avere più autori e un autore può scrivere più libri: è una relazione **N-N**. Serve una tabella in mezzo che contiene solo le coppie (libro, autore).
- Un prestito collega **un** socio e **un** libro in una certa data: la tabella dei prestiti avrà due chiavi esterne.

*Formato consigliato:*
```
soci (id PK, nome, cognome, email)
prestiti (id PK, socio_id FK → soci.id, ...)
```

---

## 4.3 – Soci e prestiti (45 minuti) – `es-4-3.sql`

**Obiettivo:** creare due tabelle nuove collegate a quelle esistenti, usando vincoli e chiavi esterne.

Lavora nel database `lezione_sql`, che contiene già la tabella `libri`.

### Promemoria di sintassi

```sql
CREATE TABLE nome_tabella (
    id INT AUTO_INCREMENT PRIMARY KEY,   -- numero assegnato in automatico
    colonna1 VARCHAR(100) NOT NULL,      -- testo obbligatorio
    colonna2 VARCHAR(100) UNIQUE,        -- nessun doppione
    colonna3 DATE DEFAULT (CURRENT_DATE),-- se non la scrivi, mette la data di oggi
    altra_id INT,                        -- conterrà un id di un'altra tabella
    FOREIGN KEY (altra_id) REFERENCES altra_tabella(id)
);
```

- `NOT NULL` e `UNIQUE` si possono usare insieme sulla stessa colonna.
- La colonna della chiave esterna deve avere **lo stesso tipo** dell'`id` a cui punta (di solito `INT`).
- La tabella a cui punti deve **esistere già**.

### 1. Tabella `soci`
Colonne:
- `id` – chiave primaria, automatica;
- `nome`, `cognome` – testo;
- `email` – obbligatoria (`NOT NULL`) e senza doppioni (`UNIQUE`);
- `data_iscrizione` – tipo `DATE`, con valore predefinito la data di oggi: `DEFAULT (CURRENT_DATE)`.

### 2. Tabella `prestiti`
Colonne:
- `id` – chiave primaria, automatica;
- `libro_id` – chiave esterna verso `libri(id)`;
- `socio_id` – chiave esterna verso `soci(id)`;
- `data_prestito` – `DATE`, obbligatoria;
- `data_restituzione` – `DATE`, **senza** `NOT NULL`.

Se `data_restituzione` è `NULL` (vuota) significa che il libro non è ancora stato restituito.

**Attenzione all'ordine:** crea prima `soci`, poi `prestiti`, perché `prestiti` fa riferimento a `soci`.

### 3. Inserisci i dati
- **3 soci.** Puoi non scrivere `data_iscrizione` in uno dei soci, poi controlla in HeidiSQL che il database abbia messo la data di oggi.
- **4 prestiti**, di cui **almeno uno ancora aperto** (senza `data_restituzione`).

```sql
INSERT INTO soci (nome, cognome, email) VALUES
    ('...', '...', '...'),
    ('...', '...', '...');
```

Non scrivere l'`id`: lo assegna il database. Per sapere quali id hanno i soci e i libri, apri le tabelle nella scheda **Dati** prima di inserire i prestiti.

Per lasciare vuota la restituzione puoi non nominare la colonna, oppure scrivere `NULL` senza virgolette.

### 4. Il socio che non esiste
Prova a inserire un prestito con `socio_id = 99`, che non esiste.
Scrivi in un commento:
- il messaggio di errore;
- quale regola lo ha causato;
- perché è utile che il database si comporti così.

---

## 4.4 – Il catalogo completo (40 minuti) – `es-4-4.sql`

**Obiettivo:** inserire dati in tabelle collegate, rispettando l'ordine imposto dalle chiavi esterne.

Aggiungi questi libri a `lezione_sql`:

| Titolo | Anno | Pagine | Genere | Autore |
|---|---|---|---|---|
| Le città invisibili | 1972 | 168 | Romanzo | Italo Calvino |
| Marcovaldo | 1963 | 160 | Racconti | Italo Calvino |
| Il pendolo di Foucault | 1988 | 509 | Romanzo | Umberto Eco |
| Se questo è un uomo | 1947 | 220 | Saggio | Primo Levi |
| Il giorno della civetta | 1961 | 130 | Giallo | Leonardo Sciascia |

### Passo 0 – Controlla cosa c'è già
Apri in HeidiSQL le tabelle dei generi e degli autori e scrivi in un commento:
- quali generi esistono già (Saggio c'è sicuramente);
- quali autori esistono già.

Inserisci **solo quello che manca**. Se inserisci un autore due volte, avrai due righe diverse per la stessa persona: è proprio il problema visto nel 4.2.

### Passo 1 – I generi mancanti
Se Romanzo, Racconti o Giallo non ci sono, inseriscili.

### Passo 2 – Gli autori nuovi
Inserisci solo gli autori che non esistono ancora.

### Passo 3 – I libri
Ogni libro ha una colonna con l'**id del genere**, non il nome. Apri la tabella dei generi e copia il numero giusto.

### Passo 4 – Le righe di `libri_autori`
Questa tabella contiene solo coppie di numeri: l'id del libro e l'id dell'autore.
- Italo Calvino ha scritto due libri, quindi compare in **due** righe.
- Ogni libro della tabella sopra ha un solo autore, quindi servono **5 righe**.

```sql
INSERT INTO libri_autori (libro_id, autore_id) VALUES
    (?, ?),   -- Le città invisibili – Italo Calvino
    ...
```

**Perché quest'ordine:** `libri` punta a `generi`, e `libri_autori` punta sia a `libri` sia ad `autori`. Una riga che fa riferimento a un'altra può essere inserita solo dopo di essa.

**Dopo ogni INSERT** aggiorna la tabella in HeidiSQL (F5) e annota gli id assegnati.

**Controllo finale:** nella scheda Dati di `libri_autori` devono esserci 5 righe nuove, e ogni numero deve corrispondere a un libro e a un autore che esistono.

---

## 4.5 – Primi SELECT (30 minuti) – `es-4-5.sql`

**Obiettivo:** leggere i dati con filtri, ordinamenti e limiti.

### Promemoria di sintassi

```sql
SELECT colonne          -- quali colonne (* = tutte)
FROM tabella            -- da quale tabella
WHERE condizione        -- quali righe (facoltativo)
ORDER BY colonna        -- in che ordine (ASC crescente, DESC decrescente)
LIMIT numero;           -- quante righe al massimo
```

Le parti vanno scritte **sempre in questo ordine**.

Scrivi una query per ogni punto e mettici sopra un commento con il numero:

1. **Tutti i libri ordinati per titolo.**
   Tutte le colonne, ordine alfabetico.

2. **Titolo e anno dei libri usciti dal 1960 in poi.**
   Solo due colonne. "Dal 1960 in poi" comprende il 1960: scegli bene tra `>` e `>=`.

3. **I 3 libri più lunghi.**
   Ordina per pagine dal più grande al più piccolo, poi tieni solo le prime 3 righe.

4. **I libri del genere con id 1.**
   Filtra sulla colonna che contiene l'id del genere.

5. **Gli autori ordinati per cognome.**
   Se due autori hanno lo stesso cognome, puoi aggiungere un secondo criterio: `ORDER BY cognome, nome`.

6. **I prestiti ancora aperti.**
   Usa `data_restituzione IS NULL`.
   **Attenzione:** `= NULL` non funziona, non restituisce mai nessuna riga. Con `NULL` si usa sempre `IS NULL` o `IS NOT NULL`.

**Controlla i risultati:** per ogni query guarda le righe ottenute e verifica che siano davvero quelle che ti aspetti, confrontandole con la scheda Dati.

---

## 4.6 – Una palestra (40 minuti) – `es-4-6.md` e `es-4-6.sql`

**Obiettivo:** progettare da zero un piccolo database, dalla carta al codice.

### La situazione
In una palestra ci sono **iscritti**, **corsi**, **istruttori** e **sale**.
- Un corso ha **un solo** istruttore.
- Un corso si tiene in **una** sala.
- Un iscritto può frequentare **più** corsi, e un corso ha più iscritti.
- Per ogni iscrizione di una persona a un corso servono **la data di inizio** e **se il pagamento è saldato**.

### 1. Relazioni e cardinalità – in `es-4-6.md`
Per ogni coppia di entità che sono collegate, scrivi la relazione e il tipo.

Come capire il tipo: fai la domanda nei due versi.
- "Un corso quanti istruttori ha?" → uno.
- "Un istruttore quanti corsi può tenere?" → più di uno.
- Uno da una parte e più dall'altra = **1-N**. La chiave esterna va nella tabella dalla parte "N" (qui: nei corsi).
- Più da entrambe le parti = **N-N**. Serve una tabella in mezzo.

*Formato:*
```
istruttori – corsi: 1-N (un istruttore tiene più corsi, un corso ha un solo istruttore)
```

**Suggerimento importante:** la data di inizio e il pagamento non appartengono né all'iscritto né al corso, ma alla **loro iscrizione**. Chiediti in quale tabella vanno messi.

### 2. I `CREATE TABLE` – in `es-4-6.sql`
In cima al file:
```sql
CREATE DATABASE palestra;
USE palestra;
```

Poi crea le tabelle, ognuna con chiave primaria e, dove serve, chiavi esterne.
- Crea **prima** le tabelle che non dipendono da nessuno (quelle senza chiavi esterne), **poi** quelle che le richiamano.
- Per "pagamento saldato" (sì/no) usa il tipo `BOOLEAN`: vale `TRUE` o `FALSE`. In MariaDB viene salvato come `1` o `0`.

### 3. I dati
Inserisci **almeno 3 righe per ogni tabella**, rispettando lo stesso ordine dei `CREATE`.
Nella tabella delle iscrizioni fai in modo che:
- almeno un iscritto frequenti più di un corso;
- almeno un'iscrizione non sia ancora pagata.

---

## Errori frequenti

| Messaggio o sintomo | Causa probabile |
|---|---|
| `Table '...' already exists` | Hai rieseguito un `CREATE`. Aggiungi `DROP TABLE IF EXISTS nome;` prima del `CREATE`, oppure usa `CREATE TABLE IF NOT EXISTS`. |
| `Cannot delete or update a parent row` | Stai cancellando una tabella a cui un'altra fa riferimento. Cancella prima la tabella che contiene la chiave esterna. |
| `Failed to open the referenced table` o errore 150 | La tabella della chiave esterna non esiste ancora, oppure i tipi delle due colonne non coincidono. |
| `No database selected` | Manca `USE nome_database;` in cima al file. |
| `You have an error in your SQL syntax ... near '...'` | Errore di scrittura appena **prima** del punto indicato: spesso una virgola mancante o in più, o una parentesi non chiusa. |
| La query non dà errori ma non restituisce righe | Controlla il `WHERE`: maiuscole/minuscole, spazi nei testi, `= NULL` al posto di `IS NULL`. |
| La tabella nuova non compare a sinistra | Premi F5 sull'albero di HeidiSQL. |
