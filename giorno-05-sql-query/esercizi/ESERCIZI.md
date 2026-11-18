# Giorno 5 – Esercizi del pomeriggio (SQL)

## Prima di iniziare

1. **Controlla il database `biblioteca`.**
   Se non esiste, o se l'hai modificato nei giorni scorsi, ricrealo.
   Esegui questi due file della cartella `lezione`, in quest'ordine:
   - `00-schema-biblioteca.sql` (crea le tabelle)
   - `00-dati-biblioteca.sql` (inserisce i dati)

2. **Crea un file `esercizi.sql`.**
   Scrivi lì tutte le query. Prima di ogni query metti un commento con il numero dell'esercizio:

   ```sql
   -- 5.1
   SELECT ...;

   -- 5.2
   SELECT ...;
   ```

3. **Controlla ogni risultato prima di guardare la soluzione.**
   Chiediti: il risultato ha senso? Quante righe mi aspettavo?
   Se non lo sai, guarda prima i dati della tabella con `SELECT * FROM ...`.

In ogni esercizio la riga **Ti serve** indica le parole chiave SQL da usare.

---

## Livello 1 – Interrogare una sola tabella (40 minuti)

**5.1** Mostra tutti i libri, ordinati per anno di pubblicazione: prima il più recente, ultimo il più vecchio.
*Ti serve:* `ORDER BY ... DESC`

**5.2** Mostra i libri pubblicati dal 1960 al 1975. Devono comparire anche quelli del 1960 e quelli del 1975.
*Ti serve:* `BETWEEN` oppure `>=` e `<=`

**5.3** Mostra i libri che hanno le lettere "il" nel titolo.
Attenzione: la query troverà anche titoli in cui "il" sta dentro un'altra parola (per esempio "Sicilia"). Va bene così: guarda il risultato e controlla quali titoli sono usciti.
*Ti serve:* `LIKE` con il simbolo `%`

**5.4** Mostra i 3 libri con più pagine.
*Ti serve:* `ORDER BY ... DESC` e `LIMIT`

**5.5** Mostra i libri senza numero di pagine (il campo è vuoto).
*Ti serve:* `IS NULL` (non funziona `= NULL`)

**5.6** Mostra l'elenco delle nazionalità degli autori. Ogni nazionalità deve comparire una volta sola.
*Ti serve:* `DISTINCT`

---

## Livello 2 – Contare e calcolare (30 minuti)

**5.7** Quanti libri ci sono in tutto? Il risultato è un solo numero.
*Ti serve:* `COUNT(*)`

**5.8** Calcola, sul numero di pagine dei libri:
- la media
- il valore più piccolo
- il valore più grande

Il risultato è una sola riga con tre colonne.
*Ti serve:* `AVG`, `MIN`, `MAX`

**5.9** Quanti libri ci sono per ogni genere?
Il risultato ha una riga per genere: id del genere e numero di libri. Il nome del genere non serve (lo aggiungi nella 5.13).
*Ti serve:* `COUNT` e `GROUP BY`

**5.10** In quali anni sono stati pubblicati 2 o più libri?
Mostra l'anno e quanti libri sono usciti in quell'anno.
*Ti serve:* `GROUP BY` e `HAVING` (non `WHERE`: la condizione è sul conteggio)

---

## Livello 3 – Unire più tabelle con JOIN (70 minuti)

**5.11** Mostra il titolo di ogni libro e accanto il nome del suo editore.
Nota: i libri senza editore qui **non** compaiono.
*Ti serve:* `INNER JOIN`

**5.12** Come la 5.11, ma devono comparire **anche** i libri senza editore. Al posto del nome dell'editore vedrai `NULL`.
Confronta il numero di righe con quello della 5.11.
*Ti serve:* `LEFT JOIN`

**5.13** Per ogni genere mostra il nome del genere e quanti libri ha.
Devono comparire anche i generi che non hanno libri, con il numero 0.
*Ti serve:* `LEFT JOIN`, `COUNT` e `GROUP BY`
Attenzione: `COUNT(*)` conta 1 anche per i generi senza libri. Conta invece una colonna della tabella dei libri.

**5.14** Mostra il titolo di ogni libro e il nome del suo autore.
Prima guarda lo schema: come sono collegati libri e autori? Ti servono due tabelle o tre?
*Ti serve:* `JOIN`

**5.15** Quali autori non hanno nessun libro nel catalogo?
*Ti serve:* `LEFT JOIN` e `IS NULL`

**5.16** Quali soci non hanno mai preso un libro in prestito?
È lo stesso ragionamento della 5.15, con altre tabelle.
*Ti serve:* `LEFT JOIN` e `IS NULL`

**5.17** Mostra i prestiti non ancora restituiti (la data di restituzione è vuota).
Per ogni prestito mostra:
- il nome del socio
- il titolo del libro
- la data del prestito
- quanti giorni sono passati dal prestito a oggi

*Ti serve:* `JOIN`, `IS NULL`, `DATEDIFF(CURDATE(), ...)`

---

## Livello 4 – JOIN e conteggi insieme (40 minuti)

**5.18** Mostra i 5 libri prestati più volte: titolo e numero di prestiti, dal più prestato al meno prestato.
*Ti serve:* `JOIN`, `COUNT`, `GROUP BY`, `ORDER BY ... DESC`, `LIMIT`

**5.19** Considera solo i prestiti già restituiti. Per ogni socio mostra:
- il nome del socio
- quanti prestiti ha restituito
- quanti giorni sono durati in media i suoi prestiti (dal giorno del prestito al giorno della restituzione)

Bastano i soci con almeno un prestito restituito.
*Ti serve:* `JOIN`, `IS NOT NULL`, `COUNT`, `AVG(DATEDIFF(...))`, `GROUP BY`

---

## Livello 5 – Modificare i dati e scoprire i vincoli (30 minuti)

**5.20** Il libro "Il nome della rosa" ha un prestito non ancora restituito. Registra la restituzione con la data di oggi.
Prima scrivi una `SELECT` che trovi **solo** quel prestito. Quando restituisce una riga sola, trasformala in `UPDATE` con lo stesso `WHERE`.
Attenzione: un `UPDATE` senza `WHERE` modifica tutte le righe della tabella.
*Ti serve:* `UPDATE ... SET ... WHERE`, `CURDATE()`

**5.21** Prova a cancellare il genere "Giallo".
- Cosa succede?
- Leggi il messaggio di errore: quale tabella lo impedisce, e perché?

*Ti serve:* `DELETE FROM ... WHERE`

**5.22** Cancella l'editore Sellerio. Poi cancella l'editore Adelphi.
- Sono riuscite le cancellazioni?
- Guarda i libri che erano di Adelphi: cosa è cambiato?
- Perché qui il database si comporta in modo diverso dalla 5.21?
  Suggerimento: guarda nello schema come sono definite le chiavi esterne (`FOREIGN KEY ... ON DELETE ...`).

---

## Extra – Creare una vista

Una vista è una query salvata con un nome. Dopo averla creata si interroga come una tabella.

1. Crea una vista chiamata `v_prestiti_aperti` che mostri, per i prestiti non ancora restituiti: nome del socio, titolo del libro, data del prestito.
   Puoi partire dalla query della 5.17.

   ```sql
   CREATE VIEW v_prestiti_aperti AS
   SELECT ...;
   ```

2. Interrogala:

   ```sql
   SELECT * FROM v_prestiti_aperti;
   ```

3. Dopo la 5.20 il prestito di "Il nome della rosa" compare ancora nella vista? Perché?
