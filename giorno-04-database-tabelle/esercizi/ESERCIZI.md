# Giorno 4 – Esercizi del pomeriggio (database, tabelle, primi SELECT)

Si lavora in HeidiSQL (o DBeaver), collegati con l'utente `corso`.
Scrivi le soluzioni in file `.sql` nella tua cartella, uno per esercizio: si rieseguono quando serve.

---

## 4.1 – Gli script della lezione (15 minuti)
Esegui nell'ordine `02-crea-tabelle.sql`, `03-inserisci.sql`, `04-select.sql`.
Poi, in HeidiSQL, apri ogni tabella di `lezione_sql` e guarda la scheda **Dati**.
Prova le tre righe di errore in fondo a `03-inserisci.sql` e scrivi in un commento quale
regola (NOT NULL, UNIQUE, FOREIGN KEY) ha fermato ciascuna.

## 4.2 – Normalizzare su carta (30 minuti) – `es-4-2.md`
La biblioteca teneva i prestiti in un foglio Excel:

| socio | email | titolo | autori | data prestito |
|---|---|---|---|---|
| Anna Rossi | anna@example.com | Il nome della rosa | Umberto Eco | 2026-10-02 |
| Anna Rossi | anna@example.com | La donna della domenica | Carlo Fruttero, Franco Lucentini | 2026-10-15 |
| Marco Bianchi | marco@example.com | Il nome della rosa | Umberto Eco | 2026-10-20 |

1. Elenca le informazioni scritte più di una volta.
2. Quale cella contiene più valori?
3. Cosa succede se Anna cambia email?
4. Proponi le tabelle (nome e colonne), indicando chiavi primarie ed esterne.

## 4.3 – Soci e prestiti (45 minuti) – `es-4-3.sql`
Nel database `lezione_sql` aggiungi:
1. `soci`: id, nome, cognome, email (obbligatoria e senza doppioni), data di iscrizione
   (`DATE`, con valore predefinito la data di oggi: `DEFAULT (CURRENT_DATE)`).
2. `prestiti`: id, libro_id e socio_id (chiavi esterne), data_prestito (obbligatoria),
   data_restituzione (`NULL` = libro ancora fuori).
3. Inserisci 3 soci e 4 prestiti, di cui almeno uno ancora aperto.
4. Prova a inserire un prestito per il socio 99: cosa risponde il database?

## 4.4 – Il catalogo completo (40 minuti) – `es-4-4.sql`
Aggiungi a `lezione_sql` questi dati (il genere Saggio esiste già):

| Titolo | Anno | Pagine | Genere | Autore |
|---|---|---|---|---|
| Le città invisibili | 1972 | 168 | Romanzo | Italo Calvino |
| Marcovaldo | 1963 | 160 | Racconti | Italo Calvino |
| Il pendolo di Foucault | 1988 | 509 | Romanzo | Umberto Eco |
| Se questo è un uomo | 1947 | 220 | Saggio | Primo Levi |
| Il giorno della civetta | 1961 | 130 | Giallo | Leonardo Sciascia |

Prima gli autori nuovi, poi i libri, poi le righe di `libri_autori`. Per sapere gli id
assegnati, guarda le tabelle in HeidiSQL dopo ogni INSERT.

## 4.5 – Primi SELECT (30 minuti) – `es-4-5.sql`
1. Tutti i libri ordinati per titolo.
2. Titolo e anno dei libri usciti dal 1960 in poi.
3. I 3 libri più lunghi.
4. I libri del genere con id 1.
5. Gli autori ordinati per cognome.
6. I prestiti ancora aperti (`data_restituzione IS NULL`).

## 4.6 – Una palestra (40 minuti) – `es-4-6.md` e `es-4-6.sql`
Iscritti, corsi, istruttori, sale. Un corso ha un solo istruttore e si tiene in una sala;
un iscritto può frequentare più corsi; per ogni iscrizione a un corso servono la data di inizio
e se il pagamento è saldato.
1. Scrivi le relazioni e la loro cardinalità (1-N o N-N).
2. Scrivi i `CREATE TABLE` in un database `palestra`, con chiavi primarie ed esterne.
3. Inserisci almeno 3 righe per tabella.
