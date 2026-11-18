# Giorno 5 – Esercizi del pomeriggio (SQL) – versione spiegata

Oggi scrivi query SQL sul database `biblioteca`.
Gli esercizi sono divisi in 5 livelli più un extra.
Ogni livello introduce un argomento nuovo.

Ogni esercizio ha questa forma:

- la richiesta;
- la riga **Ti serve**, con le parole chiave SQL da usare;
- una spiegazione della sintassi, con un esempio;
- in alcuni esercizi, i passi da seguire uno alla volta;
- le **trappole**, cioè gli errori più comuni;
- il **Controllo**, cioè come capire se il risultato è giusto.

> **Importante: gli esempi mostrano la forma, non sono la soluzione.**
> Gli esempi usano un database diverso, inventato: un'azienda con le tabelle `dipendenti`, `reparti`, `progetti` e `trasferte`.
> Devi adattare la forma alle tabelle della biblioteca.

---

## Prima di iniziare

### 1. Ricrea il database `biblioteca`

Se il database non esiste, o se l'hai modificato nei giorni scorsi, ricrealo.
Esegui questi due file della cartella `lezione`, in quest'ordine:

1. `00-schema-biblioteca.sql`: crea le tabelle.
2. `00-dati-biblioteca.sql`: inserisce i dati.

Come si fa in HeidiSQL:

1. Menu **File > Carica file SQL** e scegli il file.
2. Premi **F9**: esegue tutto il contenuto della scheda.
3. Premi **F5** nell'albero a sinistra: aggiorna l'elenco dei database e delle tabelle.

Ripeti per il secondo file.

Attenzione: il primo file cancella il database e lo ricrea da zero.
Tutte le modifiche fatte prima vanno perse.
È quello che vuoi: così parti da dati puliti.

### 2. Guarda le tabelle prima di scrivere query

Nell'albero a sinistra apri `biblioteca` e clicca su una tabella.

- La scheda **Struttura** (o "Tabella") mostra le colonne: nome, tipo, se possono essere vuote.
- La scheda **Dati** mostra le righe.

Le tabelle del database sono sette:

| Tabella | Cosa contiene |
|---|---|
| `libri` | titolo, anno, pagine, e i numeri dell'editore e del genere |
| `editori` | nome e città dell'editore |
| `generi` | nome del genere (Romanzo, Giallo, ...) |
| `autori` | nome, cognome, nazionalità, anno di nascita |
| `libri_autori` | quale autore ha scritto quale libro |
| `soci` | gli iscritti alla biblioteca |
| `prestiti` | quale socio ha preso quale libro, e quando |

Puoi anche guardare tutti i dati di una tabella con una query:

```sql
SELECT * FROM nome_tabella;
```

L'asterisco `*` vuol dire "tutte le colonne".

### 3. Crea il file `esercizi.sql`

Scrivi tutte le query in un unico file `esercizi.sql`.
Prima di ogni query metti un commento con il numero dell'esercizio.

Un **commento** è una riga che il database ignora.
In SQL un commento comincia con due trattini e uno spazio: `-- `.

```sql
-- 5.1
SELECT ...;

-- 5.2
SELECT ...;
```

Ogni query finisce con il punto e virgola `;`.

### 4. Esegui una query alla volta

In HeidiSQL:

- **F9** esegue tutto il file. Con molte query, i risultati si aprono in tante schede in basso.
- **Ctrl+F9** esegue solo il testo selezionato.

Il metodo consigliato: seleziona solo la query che stai provando e premi **Ctrl+F9**.

### 5. Controlla ogni risultato

Prima di guardare la soluzione, chiediti:

- Il risultato ha senso?
- Quante righe mi aspettavo?

Il numero di righe compare in basso nella finestra di HeidiSQL.
Se non sai quante righe aspettarti, guarda prima i dati con `SELECT * FROM ...`.

---

## Promemoria: l'ordine delle parti di una SELECT

Una query SELECT ha queste parti.
Si chiamano **clausole**.
Non sono tutte obbligatorie, ma quando ci sono vanno scritte **in quest'ordine**:

```sql
SELECT    colonne da mostrare
FROM      tabella
JOIN      altra tabella ON condizione di collegamento
WHERE     condizione sulle righe
GROUP BY  colonne per cui raggruppare
HAVING    condizione sui gruppi
ORDER BY  colonne per cui ordinare
LIMIT     numero massimo di righe
```

Se scrivi le clausole in un ordine diverso, MariaDB dà un errore di sintassi.

---

## Livello 1 – Interrogare una sola tabella (40 minuti)

### Cosa impari in questo livello

Lavori su una tabella alla volta.
Impari a:

- scegliere le righe con `WHERE`;
- ordinarle con `ORDER BY`;
- limitarne il numero con `LIMIT`;
- trovare i valori mancanti con `IS NULL`;
- togliere le ripetizioni con `DISTINCT`.

`WHERE` è la clausola che **filtra** le righe: tiene solo quelle che rispettano una condizione.

```sql
SELECT nome, cognome
FROM dipendenti
WHERE stipendio > 2000;
```

---

### 5.1 – Libri dal più recente al più vecchio

Mostra tutti i libri, ordinati per anno di pubblicazione.
Prima il più recente, ultimo il più vecchio.

*Ti serve:* `ORDER BY ... DESC`

**La sintassi.**
`ORDER BY` ordina le righe del risultato.
Di solito l'ordine è crescente: dal più piccolo al più grande.
`DESC` (da "descending") inverte l'ordine: dal più grande al più piccolo.
Per gli anni, "più grande" vuol dire "più recente".

```sql
SELECT nome, data_assunzione
FROM dipendenti
ORDER BY data_assunzione DESC;
```

**Trappole.**
- `DESC` va scritto **dopo** il nome della colonna, non prima.
- Senza `DESC` ottieni l'ordine opposto.

**Controllo.**
Devi ottenere tutti i libri: 16 righe.
Guarda la colonna dell'anno: i numeri devono scendere dall'alto verso il basso.

---

### 5.2 – Libri dal 1960 al 1975

Mostra i libri pubblicati dal 1960 al 1975.
Devono comparire anche quelli del 1960 e quelli del 1975.

*Ti serve:* `BETWEEN` oppure `>=` e `<=`

**La sintassi.**
Ci sono due modi per scrivere un intervallo.

Con `BETWEEN`:

```sql
SELECT nome, stipendio
FROM dipendenti
WHERE stipendio BETWEEN 1500 AND 2500;
```

Con due condizioni unite da `AND`:

```sql
SELECT nome, stipendio
FROM dipendenti
WHERE stipendio >= 1500 AND stipendio <= 2500;
```

I due modi danno lo stesso risultato.
`BETWEEN` include i due estremi: 1500 e 2500 compaiono.

**Trappole.**
- Con `>` e `<` (senza `=`) gli estremi restano fuori.
- In `BETWEEN` il valore più piccolo va scritto per primo.

**Controllo.**
Dovresti ottenere 8 righe.
Controlla che ci sia almeno un libro del 1975.

---

### 5.3 – Titoli che contengono "il"

Mostra i libri che hanno le lettere "il" nel titolo.

La query troverà anche titoli in cui "il" sta dentro un'altra parola (per esempio "Sicilia").
Va bene così: guarda il risultato e controlla quali titoli sono usciti.

*Ti serve:* `LIKE` con il simbolo `%`

**La sintassi.**
`LIKE` cerca un pezzo di testo dentro una colonna.
Il simbolo `%` vuol dire "qualsiasi sequenza di caratteri, anche nessuno".

| Scrittura | Significato |
|---|---|
| `LIKE 'Ma%'` | comincia con "Ma" |
| `LIKE '%ini'` | finisce con "ini" |
| `LIKE '%ar%'` | contiene "ar" in qualsiasi punto |

```sql
SELECT nome, cognome
FROM dipendenti
WHERE cognome LIKE '%ar%';
```

Questa query trova per esempio "Martini" e "Ferrari".

In MariaDB `LIKE` non distingue tra maiuscole e minuscole: "Il" e "il" sono uguali.

**Trappole.**
- Il testo va tra apici singoli: `'%ar%'`.
- Senza `%` la ricerca vuole il testo esatto: `LIKE 'ar'` trova solo il valore "ar".

**Controllo.**
Dovresti ottenere 7 righe.
Almeno un titolo contiene "il" in mezzo a una parola, e non come parola da sola. Trovalo.

---

### 5.4 – I 3 libri più lunghi

Mostra i 3 libri con più pagine.

*Ti serve:* `ORDER BY ... DESC` e `LIMIT`

**La sintassi.**
`LIMIT` dice quante righe mostrare al massimo.
Si scrive sempre alla fine della query.
Per avere "i primi N" bisogna prima ordinare, poi limitare.

```sql
SELECT nome, stipendio
FROM dipendenti
ORDER BY stipendio DESC
LIMIT 5;
```

Questa query mostra i 5 dipendenti con lo stipendio più alto.

**Trappole.**
- Senza `ORDER BY`, `LIMIT` prende righe in un ordine casuale.
- Senza `DESC` ottieni i libri più corti.

**Controllo.**
Devi ottenere 3 righe.
Il primo deve avere più pagine del secondo, il secondo più del terzo.

---

### 5.5 – Libri senza numero di pagine

Mostra i libri senza numero di pagine (il campo è vuoto).

*Ti serve:* `IS NULL` (non funziona `= NULL`)

**Cos'è NULL.**
`NULL` vuol dire "valore mancante": nessuno ha scritto niente in quella cella.
`NULL` non è zero e non è un testo vuoto.

**La sintassi.**
Per trovare i valori mancanti si scrive `IS NULL`.
Per trovare quelli presenti si scrive `IS NOT NULL`.

```sql
SELECT nome, cognome
FROM dipendenti
WHERE telefono IS NULL;
```

**Trappole.**
- `WHERE pagine = NULL` non dà errore, ma restituisce sempre 0 righe.
  Il confronto con `=` non funziona con `NULL`.
- In HeidiSQL i valori `NULL` compaiono come `(NULL)` in grigio.

**Controllo.**
Dovresti ottenere 1 riga.

---

### 5.6 – Nazionalità degli autori senza ripetizioni

Mostra l'elenco delle nazionalità degli autori.
Ogni nazionalità deve comparire una volta sola.

*Ti serve:* `DISTINCT`

**La sintassi.**
`DISTINCT` si scrive subito dopo `SELECT`.
Toglie le righe ripetute dal risultato.

```sql
SELECT DISTINCT citta
FROM dipendenti;
```

Se dieci dipendenti abitano a Milano, "Milano" compare una volta sola.

**Trappole.**
- Se metti più colonne dopo `DISTINCT`, toglie solo le righe uguali **in tutte** le colonne.
  Per questo esercizio basta una colonna.

**Controllo.**
Prova prima la query senza `DISTINCT`: ottieni una riga per ogni autore (10 righe).
Con `DISTINCT` dovresti ottenere 3 righe.

---

## Livello 2 – Contare e calcolare (30 minuti)

### Cosa impari in questo livello

Le **funzioni di aggregazione** prendono tante righe e restituiscono un solo valore.

| Funzione | Cosa calcola |
|---|---|
| `COUNT(*)` | quante righe ci sono |
| `COUNT(colonna)` | quante righe hanno un valore in quella colonna (non `NULL`) |
| `SUM(colonna)` | la somma |
| `AVG(colonna)` | la media |
| `MIN(colonna)` | il valore più piccolo |
| `MAX(colonna)` | il valore più grande |

`GROUP BY` divide le righe in gruppi.
Poi la funzione di aggregazione fa un calcolo per ogni gruppo.

Esempio con dati piccoli.
Tabella `dipendenti`:

| nome | reparto_id |
|---|---|
| Rita | 1 |
| Ugo | 1 |
| Ada | 2 |

```sql
SELECT reparto_id, COUNT(*) AS quanti
FROM dipendenti
GROUP BY reparto_id;
```

Risultato:

| reparto_id | quanti |
|---|---|
| 1 | 2 |
| 2 | 1 |

`AS quanti` è un **alias**: un nome che dai a una colonna del risultato.
Serve a rendere il risultato più leggibile.

---

### 5.7 – Quanti libri in tutto

Quanti libri ci sono in tutto?
Il risultato è un solo numero.

*Ti serve:* `COUNT(*)`

**La sintassi.**

```sql
SELECT COUNT(*) AS totale
FROM dipendenti;
```

**Trappole.**
- Tra `COUNT` e la parentesi non va lo spazio: `COUNT(*)`.

**Controllo.**
Il risultato è 1 riga con 1 colonna.
Il numero deve essere uguale alle righe che hai ottenuto nella 5.1.

---

### 5.8 – Media, minimo e massimo delle pagine

Calcola, sul numero di pagine dei libri:

- la media
- il valore più piccolo
- il valore più grande

Il risultato è una sola riga con tre colonne.

*Ti serve:* `AVG`, `MIN`, `MAX`

**La sintassi.**
Puoi mettere più funzioni nella stessa SELECT, separate da virgole.

```sql
SELECT AVG(stipendio) AS media,
       MIN(stipendio) AS minimo,
       MAX(stipendio) AS massimo
FROM dipendenti;
```

**Trappole.**
- Le funzioni di aggregazione ignorano i valori `NULL`.
  Il libro senza pagine (quello della 5.5) non entra nella media.
- La media può avere molti decimali. Va bene così.

**Controllo.**
1 riga, 3 colonne.
Il massimo deve essere uguale alle pagine del primo libro della 5.4.

---

### 5.9 – Quanti libri per ogni genere

Quanti libri ci sono per ogni genere?
Il risultato ha una riga per genere: id del genere e numero di libri.
Il nome del genere non serve (lo aggiungi nella 5.13).

*Ti serve:* `COUNT` e `GROUP BY`

**La sintassi.**
Guarda l'esempio all'inizio del livello.
La colonna che metti in `GROUP BY` deve comparire anche dopo `SELECT`.

**Trappole.**
- Se dimentichi `GROUP BY`, ottieni un solo numero: il totale di tutti i libri.
- Il numero del genere sta in una colonna della tabella dei libri. Guarda la scheda Struttura di `libri`.

**Controllo.**
Somma a mano i numeri della colonna del conteggio.
Il totale deve essere uguale al risultato della 5.7.

---

### 5.10 – Anni con 2 o più libri

In quali anni sono stati pubblicati 2 o più libri?
Mostra l'anno e quanti libri sono usciti in quell'anno.

*Ti serve:* `GROUP BY` e `HAVING` (non `WHERE`: la condizione è sul conteggio)

**La differenza tra WHERE e HAVING.**

- `WHERE` filtra le righe **prima** di raggruppare.
- `HAVING` filtra i gruppi **dopo** averli formati.

Il conteggio esiste solo dopo il raggruppamento.
Per questo la condizione sul conteggio va in `HAVING`.

```sql
SELECT reparto_id, COUNT(*) AS quanti
FROM dipendenti
GROUP BY reparto_id
HAVING COUNT(*) > 10;
```

Questa query mostra solo i reparti con più di 10 dipendenti.

**Passi.**
1. Scrivi la query con `GROUP BY` sull'anno, senza condizione. Guarda il risultato: una riga per anno.
2. Aggiungi `HAVING` con la condizione sul conteggio.

**Trappole.**
- `WHERE COUNT(*) >= 2` dà errore: in `WHERE` non si possono usare le funzioni di aggregazione.
- "2 o più" si scrive `>= 2`, non `> 2`.

**Controllo.**
Dovresti ottenere 3 righe.
Ogni riga deve avere un conteggio di almeno 2.

---

## Livello 3 – Unire più tabelle con JOIN (70 minuti)

### Cosa impari in questo livello

Nella tabella `libri` non c'è il nome dell'editore.
C'è solo un numero: `editore_id`.
Quel numero corrisponde all'`id` di una riga della tabella `editori`.

Due termini:

- **Chiave primaria**: la colonna che identifica ogni riga in modo unico. Qui è sempre `id`.
- **Chiave esterna**: una colonna che contiene la chiave primaria di un'altra tabella. Per esempio `libri.editore_id`.

`JOIN` unisce le righe di due tabelle.
`ON` dice quali colonne devono essere uguali.

```sql
SELECT dipendenti.nome, reparti.nome
FROM dipendenti
JOIN reparti ON dipendenti.reparto_id = reparti.id;
```

**Alias delle tabelle.**
Puoi dare un nome corto a ogni tabella.
Così scrivi meno.

```sql
SELECT d.nome, r.nome AS reparto
FROM dipendenti d
JOIN reparti r ON d.reparto_id = r.id;
```

Quando due tabelle hanno una colonna con lo stesso nome (per esempio `nome` o `id`), devi scrivere davanti la tabella o l'alias: `d.nome`, `r.nome`.

### INNER JOIN e LEFT JOIN

Esempio con dati piccoli.

Tabella `dipendenti`:

| id | nome | reparto_id |
|---|---|---|
| 1 | Rita | 1 |
| 2 | Ugo | 2 |
| 3 | Ada | NULL |

Tabella `reparti`:

| id | nome |
|---|---|
| 1 | Vendite |
| 2 | Magazzino |
| 3 | Ricerca |

**INNER JOIN** (si può scrivere anche solo `JOIN`): tiene solo le righe che trovano un collegamento.

```sql
SELECT d.nome, r.nome AS reparto
FROM dipendenti d
INNER JOIN reparti r ON d.reparto_id = r.id;
```

| nome | reparto |
|---|---|
| Rita | Vendite |
| Ugo | Magazzino |

Ada sparisce: non ha reparto.
Ricerca sparisce: non ha dipendenti.

**LEFT JOIN**: tiene **tutte** le righe della tabella scritta a sinistra (dopo `FROM`), anche quelle senza collegamento.
Le colonne dell'altra tabella valgono `NULL`.

```sql
SELECT d.nome, r.nome AS reparto
FROM dipendenti d
LEFT JOIN reparti r ON d.reparto_id = r.id;
```

| nome | reparto |
|---|---|
| Rita | Vendite |
| Ugo | Magazzino |
| Ada | NULL |

Se inverti le tabelle (`FROM reparti r LEFT JOIN dipendenti d ...`), tieni tutti i reparti: compare Ricerca con `NULL` al posto del dipendente.

**La regola:** con `LEFT JOIN`, la tabella dopo `FROM` è quella di cui vuoi vedere **tutte** le righe.

---

### 5.11 – Titolo ed editore

Mostra il titolo di ogni libro e accanto il nome del suo editore.
Nota: i libri senza editore qui **non** compaiono.

*Ti serve:* `INNER JOIN`

**La sintassi.**
Guarda l'esempio di INNER JOIN qui sopra.

**Passi.**
1. Guarda la Struttura di `libri`: quale colonna contiene il numero dell'editore?
2. Guarda la Struttura di `editori`: quale colonna contiene il nome?
3. Scrivi la JOIN con `ON` che collega le due colonne giuste.

**Trappole.**
- Se scrivi solo `nome` dopo `SELECT`, MariaDB può dare l'errore "Column 'nome' in field list is ambiguous" se la colonna esiste in più tabelle. Scrivi l'alias davanti.
- Se dimentichi `ON`, ottieni tutte le combinazioni possibili: centinaia di righe.

**Controllo.**
Dovresti ottenere 15 righe: un libro in meno della 5.1.

---

### 5.12 – Titolo ed editore, anche senza editore

Come la 5.11, ma devono comparire **anche** i libri senza editore.
Al posto del nome dell'editore vedrai `NULL`.
Confronta il numero di righe con quello della 5.11.

*Ti serve:* `LEFT JOIN`

**La sintassi.**
Guarda l'esempio di LEFT JOIN all'inizio del livello.

**Trappole.**
- La tabella dei libri deve stare dopo `FROM`. Se la metti dopo `LEFT JOIN`, ottieni tutti gli editori invece di tutti i libri.

**Controllo.**
Dovresti ottenere 16 righe: una in più della 5.11.
In una riga il nome dell'editore è `NULL`.

---

### 5.13 – Nome del genere e numero di libri, anche 0

Per ogni genere mostra il nome del genere e quanti libri ha.
Devono comparire anche i generi che non hanno libri, con il numero 0.

*Ti serve:* `LEFT JOIN`, `COUNT` e `GROUP BY`

Attenzione: `COUNT(*)` conta 1 anche per i generi senza libri.
Conta invece una colonna della tabella dei libri.

**Perché COUNT(*) sbaglia.**
Riprendi l'esempio con reparti e dipendenti.
Con `FROM reparti LEFT JOIN dipendenti`, il reparto Ricerca compare in una riga con il dipendente `NULL`.

- `COUNT(*)` conta le righe: per Ricerca trova 1 riga, quindi scrive 1.
- `COUNT(d.id)` conta solo i valori non `NULL`: per Ricerca scrive 0.

```sql
SELECT r.nome, COUNT(d.id) AS dipendenti
FROM reparti r
LEFT JOIN dipendenti d ON d.reparto_id = r.id
GROUP BY r.nome;
```

**Passi.**
1. Scrivi la LEFT JOIN senza `GROUP BY`. Quale tabella va dopo `FROM`? Quella di cui vuoi vedere tutte le righe.
2. Guarda il risultato: il genere senza libri compare con `NULL` nelle colonne dei libri?
3. Aggiungi `GROUP BY` e il conteggio.

**Trappole.**
- La tabella sbagliata dopo `FROM`: il genere senza libri sparisce.
- `COUNT(*)` invece di `COUNT(colonna)`: il genere senza libri ha 1 invece di 0.

**Controllo.**
Dovresti ottenere 5 righe, una per genere.
Un genere ha 0.
La somma dei conteggi deve essere uguale al risultato della 5.7.
Confronta i numeri con quelli della 5.9.

---

### 5.14 – Titolo e autore

Mostra il titolo di ogni libro e il nome del suo autore.
Prima guarda lo schema: come sono collegati libri e autori? Ti servono due tabelle o tre?

*Ti serve:* `JOIN`

**Relazione molti a molti.**
Un libro può avere più autori.
Un autore può scrivere più libri.
Questa si chiama relazione **molti a molti** (o N-N).

In questo caso non basta una chiave esterna in una tabella.
Serve una **tabella ponte**: una tabella che contiene solo le coppie di numeri.

Esempio: un dipendente lavora su più progetti, un progetto ha più dipendenti.

| dipendenti_progetti |  |
|---|---|
| dipendente_id | progetto_id |
| 1 | 10 |
| 1 | 11 |
| 2 | 10 |

Per avere nome del dipendente e nome del progetto servono due JOIN:

```sql
SELECT d.nome, p.titolo
FROM dipendenti d
JOIN dipendenti_progetti dp ON dp.dipendente_id = d.id
JOIN progetti p ON p.id = dp.progetto_id;
```

**Passi.**
1. Nell'albero di HeidiSQL trova la tabella ponte tra libri e autori. Guarda le sue colonne.
2. Scrivi la prima JOIN: libri con la tabella ponte. Esegui. Guarda il risultato.
3. Aggiungi la seconda JOIN: tabella ponte con autori.

**Trappole.**
- Non esiste una colonna `autore_id` nella tabella dei libri. Il collegamento passa per la tabella ponte.
- Un libro con due autori compare in due righe. È corretto.

**Controllo.**
Dovresti ottenere 16 righe.
Un titolo compare due volte, con due autori diversi.
Un libro non compare: non ha autori collegati.

---

### 5.15 – Autori senza libri

Quali autori non hanno nessun libro nel catalogo?

*Ti serve:* `LEFT JOIN` e `IS NULL`

**Il metodo per trovare "chi non ha".**
1. Fai una LEFT JOIN partendo dalla tabella di cui vuoi trovare le righe "senza".
2. Le righe senza collegamento hanno `NULL` nelle colonne dell'altra tabella.
3. Tieni solo quelle righe con `WHERE ... IS NULL`.

```sql
SELECT r.nome
FROM reparti r
LEFT JOIN dipendenti d ON d.reparto_id = r.id
WHERE d.id IS NULL;
```

Questa query trova i reparti senza dipendenti.

**Passi.**
1. Quale tabella va dopo `FROM`?
2. Autori e libri sono collegati con la tabella ponte (come nella 5.14). Ti basta arrivare alla tabella ponte?
3. Scrivi la LEFT JOIN senza `WHERE`. Cerca la riga con `NULL`.
4. Aggiungi `WHERE ... IS NULL`.

**Trappole.**
- Con `JOIN` normale invece di `LEFT JOIN`, gli autori senza libri spariscono e il risultato è vuoto.
- La colonna in `IS NULL` deve essere della tabella di destra, non degli autori.

**Controllo.**
Dovresti ottenere 1 riga.

---

### 5.16 – Soci che non hanno mai preso un libro

Quali soci non hanno mai preso un libro in prestito?
È lo stesso ragionamento della 5.15, con altre tabelle.

*Ti serve:* `LEFT JOIN` e `IS NULL`

**Passi.**
1. Quale tabella va dopo `FROM`?
2. Quale tabella contiene i prestiti? Con quale colonna si collega ai soci?
3. Segui il metodo della 5.15.

**Trappole.**
- Le stesse della 5.15.

**Controllo.**
Dovresti ottenere 1 riga.

---

### 5.17 – Prestiti non ancora restituiti

Mostra i prestiti non ancora restituiti (la data di restituzione è vuota).
Per ogni prestito mostra:

- il nome del socio
- il titolo del libro
- la data del prestito
- quanti giorni sono passati dal prestito a oggi

*Ti serve:* `JOIN`, `IS NULL`, `DATEDIFF(CURDATE(), ...)`

**Le funzioni sulle date.**
- `CURDATE()` restituisce la data di oggi.
- `DATEDIFF(data1, data2)` restituisce quanti giorni ci sono tra le due date. Calcola `data1 - data2`.

```sql
SELECT nome, DATEDIFF(CURDATE(), data_assunzione) AS giorni_in_azienda
FROM dipendenti;
```

**Passi.**
1. La tabella principale è quella dei prestiti. Scrivi una SELECT su `prestiti` con la condizione sulla data di restituzione. Esegui.
2. Aggiungi la JOIN con la tabella dei soci. Esegui.
3. Aggiungi la JOIN con la tabella dei libri. Esegui.
4. Aggiungi la colonna con `DATEDIFF`.

**Trappole.**
- In `DATEDIFF` conta l'ordine: prima la data più recente, poi quella più vecchia. Al contrario ottieni numeri negativi.
- `= NULL` invece di `IS NULL`: 0 righe.

**Controllo.**
Dovresti ottenere 5 righe.
Tutti i numeri di giorni devono essere positivi.
Il prestito più vecchio deve avere il numero più grande.

---

## Livello 4 – JOIN e conteggi insieme (40 minuti)

### Cosa impari in questo livello

Metti insieme JOIN e GROUP BY.
Prima unisci le tabelle, poi raggruppi.
Ricorda l'ordine delle clausole: `FROM`, `JOIN`, `WHERE`, `GROUP BY`, `ORDER BY`, `LIMIT`.

---

### 5.18 – I 5 libri prestati più volte

Mostra i 5 libri prestati più volte: titolo e numero di prestiti, dal più prestato al meno prestato.

*Ti serve:* `JOIN`, `COUNT`, `GROUP BY`, `ORDER BY ... DESC`, `LIMIT`

**La sintassi.**
Puoi ordinare usando l'alias della colonna calcolata.

```sql
SELECT p.titolo, COUNT(*) AS persone
FROM dipendenti_progetti dp
JOIN progetti p ON p.id = dp.progetto_id
GROUP BY p.titolo
ORDER BY persone DESC
LIMIT 3;
```

**Passi.**
1. Unisci prestiti e libri. Esegui: una riga per prestito.
2. Aggiungi `GROUP BY` sul titolo e il conteggio.
3. Aggiungi `ORDER BY` e `LIMIT`.

**Trappole.**
- Se più libri hanno lo stesso numero di prestiti, `LIMIT` ne sceglie alcuni in modo non prevedibile. Il tuo quinto libro può essere diverso da quello del compagno. Non è un errore.

**Controllo.**
5 righe.
I numeri devono scendere o restare uguali dall'alto verso il basso.
Il primo libro ha 4 prestiti.

---

### 5.19 – Prestiti restituiti e durata media per socio

Considera solo i prestiti già restituiti.
Per ogni socio mostra:

- il nome del socio
- quanti prestiti ha restituito
- quanti giorni sono durati in media i suoi prestiti (dal giorno del prestito al giorno della restituzione)

Bastano i soci con almeno un prestito restituito.

*Ti serve:* `JOIN`, `IS NOT NULL`, `COUNT`, `AVG(DATEDIFF(...))`, `GROUP BY`

**La sintassi.**
Una funzione può stare dentro un'altra.
`AVG(DATEDIFF(...))` calcola prima i giorni di ogni riga, poi la media.

```sql
SELECT d.nome,
       COUNT(*) AS trasferte,
       AVG(DATEDIFF(t.data_rientro, t.data_partenza)) AS durata_media
FROM trasferte t
JOIN dipendenti d ON d.id = t.dipendente_id
WHERE t.data_rientro IS NOT NULL
GROUP BY d.id, d.nome;
```

**Perché raggruppare anche per id.**
Due soci possono avere lo stesso nome.
Se raggruppi solo per nome, finiscono nello stesso gruppo.
Raggruppare anche per `id` li tiene separati.

**Passi.**
1. Scrivi la JOIN tra prestiti e soci con la condizione sui prestiti restituiti. Esegui.
2. Aggiungi una colonna con `DATEDIFF` per ogni prestito. Controlla che i numeri siano positivi.
3. Aggiungi `GROUP BY`, `COUNT` e metti `AVG` intorno a `DATEDIFF`.

**Trappole.**
- Ordine delle date in `DATEDIFF`: prima la restituzione, poi il prestito.
- La condizione "già restituito" va in `WHERE`, non in `HAVING`: riguarda le righe, non i gruppi.

**Controllo.**
Dovresti ottenere 7 righe.
La somma della colonna del conteggio deve essere 15: i 20 prestiti totali meno i 5 della 5.17.

---

## Livello 5 – Modificare i dati e scoprire i vincoli (30 minuti)

### Cosa impari in questo livello

Modifichi e cancelli righe.
Scopri che il database a volte **rifiuta** una modifica.
Lo fa per proteggere i dati: le chiavi esterne hanno delle regole.

Le regole si scrivono nello schema, con `ON DELETE`:

| Regola | Cosa succede se cancelli la riga collegata |
|---|---|
| `ON DELETE RESTRICT` | la cancellazione è vietata: errore |
| `ON DELETE SET NULL` | la cancellazione riesce; la chiave esterna diventa `NULL` |
| `ON DELETE CASCADE` | la cancellazione riesce; vengono cancellate anche le righe collegate |

Prima di iniziare questo livello, se vuoi ripartire da dati puliti, riesegui i due file `00`.

---

### 5.20 – Restituzione di "Il nome della rosa"

Il libro "Il nome della rosa" ha un prestito non ancora restituito.
Registra la restituzione con la data di oggi.

Prima scrivi una `SELECT` che trovi **solo** quel prestito.
Quando restituisce una riga sola, trasformala in `UPDATE` con lo stesso `WHERE`.

Attenzione: un `UPDATE` senza `WHERE` modifica tutte le righe della tabella.

*Ti serve:* `UPDATE ... SET ... WHERE`, `CURDATE()`

**La sintassi.**

```sql
UPDATE dipendenti
SET telefono = '0212345'
WHERE id = 7;
```

- `UPDATE` dice quale tabella modificare.
- `SET` dice quale colonna cambiare e con quale valore.
- `WHERE` dice quali righe cambiare.

**Passi.**
1. Trova l'id del libro: una SELECT sulla tabella dei libri con il titolo.
2. Scrivi una SELECT sulla tabella dei prestiti con due condizioni unite da `AND`: il libro giusto e la restituzione mancante.
3. Esegui. Deve uscire **una riga sola**.
4. Copia la query. Cambia l'inizio da `SELECT * FROM` a `UPDATE ... SET ...`. Lascia il `WHERE` uguale.
5. Esegui l'UPDATE.
6. Riesegui la SELECT del passo 2: ora deve dare 0 righe.

**Trappole.**
- Se usi solo la condizione sul libro, modifichi anche i prestiti vecchi già restituiti di quel libro.
- Se la SELECT del passo 3 dà 0 righe, forse hai già fatto questa modifica (per esempio eseguendo il file `04-modifica.sql` della lezione). Riesegui i file `00`.
- Un UPDATE non si annulla con Ctrl+Z.

**Controllo.**
In basso HeidiSQL scrive quante righe sono state modificate. Deve essere 1.

---

### 5.21 – Cancellare il genere "Giallo"

Prova a cancellare il genere "Giallo".

- Cosa succede?
- Leggi il messaggio di errore: quale tabella lo impedisce, e perché?

*Ti serve:* `DELETE FROM ... WHERE`

**La sintassi.**

```sql
DELETE FROM reparti
WHERE nome = 'Ricerca';
```

**Dove guardare.**
- Leggi il messaggio di errore per intero. Contiene il nome di una tabella e il nome di un vincolo.
- Apri il file `00-schema-biblioteca.sql` e cerca quel vincolo. Guarda cosa c'è scritto dopo `ON DELETE`.
- Guarda la tabella dell'inizio del livello.

**Trappole.**
- `DELETE` senza `WHERE` cancella tutte le righe della tabella.

**Controllo.**
Dopo il tentativo, esegui una SELECT sulla tabella dei generi. "Giallo" deve esserci ancora.

---

### 5.22 – Cancellare Sellerio e Adelphi

Cancella l'editore Sellerio.
Poi cancella l'editore Adelphi.

- Sono riuscite le cancellazioni?
- Guarda i libri che erano di Adelphi: cosa è cambiato?
- Perché qui il database si comporta in modo diverso dalla 5.21?
  Suggerimento: guarda nello schema come sono definite le chiavi esterne (`FOREIGN KEY ... ON DELETE ...`).

*Ti serve:* `DELETE FROM ... WHERE`

**Passi.**
1. **Prima** di cancellare Adelphi, guarda i suoi libri: una SELECT sulla tabella dei libri con il numero di Adelphi. Annota i titoli.
2. Cancella Sellerio. Leggi il messaggio in basso: quante righe sono state cancellate?
3. Cancella Adelphi. Leggi il messaggio.
4. Riguarda i libri annotati al passo 1. Cerca per titolo, non per editore.

**Dove guardare per il "perché".**
- Nello schema cerca la chiave esterna tra libri ed editori. Guarda cosa c'è dopo `ON DELETE`.
- Confronta con quella tra libri e generi, che hai visto nella 5.21.

**Trappole.**
- Se al passo 4 cerchi i libri con il numero di Adelphi, non li trovi più. Cerca per titolo.

**Controllo.**
Entrambe le cancellazioni devono riuscire senza errori.

---

## Extra – Creare una vista

Una **vista** è una query salvata con un nome.
Dopo averla creata si interroga come una tabella.
La vista non contiene dati suoi: ogni volta che la interroghi, esegue di nuovo la query.

1. Crea una vista chiamata `v_prestiti_aperti` che mostri, per i prestiti non ancora restituiti: nome del socio, titolo del libro, data del prestito.
   Puoi partire dalla query della 5.17.

   ```sql
   CREATE VIEW v_prestiti_aperti AS
   SELECT ...;
   ```

   Esempio sul database dell'azienda:

   ```sql
   CREATE VIEW v_senza_telefono AS
   SELECT nome, cognome
   FROM dipendenti
   WHERE telefono IS NULL;
   ```

2. Interrogala:

   ```sql
   SELECT * FROM v_prestiti_aperti;
   ```

3. Dopo la 5.20 il prestito di "Il nome della rosa" compare ancora nella vista? Perché?
   Dove guardare: rileggi la frase sopra su cosa fa una vista ogni volta che la interroghi.

**Trappole.**
- Dopo `CREATE VIEW`, premi F5 nell'albero: la vista compare sotto il database.
- Se provi a creare due volte una vista con lo stesso nome, ottieni un errore. Usa `CREATE OR REPLACE VIEW`.
- In una vista ogni colonna deve avere un nome diverso. Se hai due colonne `nome`, dai un alias a una delle due.

**Controllo.**
La vista deve dare le stesse righe della 5.17, se non hai ancora fatto la 5.20.

---

## Errori frequenti

| Messaggio o sintomo | Causa probabile |
|---|---|
| `You have an error in your SQL syntax ... near '...'` | Errore di scrittura. Guarda il testo dopo `near`: l'errore è subito prima. Spesso: virgola mancante o in più, clausole in ordine sbagliato, apice non chiuso. |
| `Table 'biblioteca.xxx' doesn't exist` | Nome della tabella sbagliato, oppure non hai eseguito i file `00`. |
| `No database selected` | Seleziona `biblioteca` nell'albero, oppure scrivi `USE biblioteca;` all'inizio del file. |
| `Unknown column 'xxx' in 'field list'` | Nome della colonna sbagliato. Controlla nella scheda Struttura. |
| `Unknown column 'xxx' in 'where clause'` | Colonna sbagliata nel WHERE, oppure hai usato nel WHERE un alias definito nella SELECT. |
| `Column 'xxx' in field list is ambiguous` | La colonna esiste in due tabelle della JOIN. Scrivi l'alias davanti: `l.titolo`. |
| `Invalid use of group function` | Hai usato `COUNT`, `AVG` ecc. dentro `WHERE`. Usa `HAVING`. |
| La query con `= NULL` dà 0 righe | Usa `IS NULL`. |
| Centinaia di righe dopo una JOIN | Manca `ON`, oppure la condizione di `ON` collega le colonne sbagliate. |
| Un gruppo con 1 invece di 0 | Hai usato `COUNT(*)` con LEFT JOIN. Conta una colonna della tabella di destra. |
| Con LEFT JOIN mancano le righe "senza" | La tabella dopo `FROM` è quella sbagliata. |
| Numeri negativi da `DATEDIFF` | Hai invertito le due date. |
| `Cannot delete or update a parent row: a foreign key constraint fails` | Ci sono righe in un'altra tabella collegate a quella che vuoi cancellare, e la regola è `RESTRICT`. È il comportamento atteso nella 5.21. |
| `Table 'v_prestiti_aperti' already exists` | La vista esiste già. Usa `CREATE OR REPLACE VIEW`. |
| Lettere accentate sbagliate nei titoli (es. `cittÃ `) | Il file dei dati è stato caricato senza UTF-8. Ricarica i file `00` da HeidiSQL. |
