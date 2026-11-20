# Giorno 6 – Esercizi del pomeriggio (PHP e database)

Copia la cartella `partenza` e rinominala `catalogo`: è il catalogo della lezione, con
`config.php` dentro la cartella. Il database è `biblioteca` del giorno 5: se l'hai modificato
troppo, riesegui i due script `00-...sql`. Avvia con `php -S localhost:8000` dalla cartella `catalogo`.

Regola per tutti gli esercizi: ogni valore che arriva da `$_GET` o `$_POST` entra nella query
**solo** con `prepare` ed `execute`, e si stampa **solo** con `e()`.

---

## 6.1 – La pagina degli autori (40 minuti) – `autori.php`
1. Elenco degli autori in ordine di cognome, con il numero dei loro libri
   (JOIN con `libri_autori` e `GROUP BY`). Devono comparire anche gli autori senza libri,
   con 0 (`LEFT JOIN` e `COUNT(la.libro_id)`).
2. Ogni nome è un link a `autore.php?id=...`.
3. Aggiungi il link "Autori" nella testata.

## 6.2 – La pagina di un autore (40 minuti) – `autore.php`
1. Legge l'autore con l'id dell'indirizzo; se non esiste, 404.
2. Mostra nome, cognome, nazionalità, anno di nascita.
3. Sotto, l'elenco dei suoi libri, con il link al dettaglio.
4. Nel dettaglio del libro (`libro.php`) i nomi degli autori diventano link alla loro pagina.

## 6.3 – Nuovo autore (50 minuti) – `nuovo-autore.php`
Sul modello di `nuovo.php`:
1. Campi: nome, cognome (obbligatori, al massimo 60 caratteri), nazionalità (facoltativa),
   anno di nascita (facoltativo, tra 1000 e l'anno corrente).
2. Errori accanto ai campi e valori inseriti mantenuti dopo un errore.
3. Se è tutto valido: INSERT e redirect alla pagina del nuovo autore.
4. Prova a scrivere `<b>Mario</b>` come nome: come compare nella pagina dell'autore?

## 6.4 – Modificare un libro (50 minuti) – `modifica.php`
1. `modifica.php?id=8`: il form di `nuovo.php` già compilato con i dati del libro
   (con GET si legge il libro dal database, con POST si salvano le modifiche).
2. Stessa validazione di `nuovo.php`.
3. Se valido: `UPDATE libri SET ... WHERE id = ?` e redirect al dettaglio.
4. Nel dettaglio aggiungi il link "Modifica".

## 6.5 – Eliminare un libro (30 minuti) – `elimina.php`
1. Nel dettaglio del libro, un form con `method="post"` e `action="elimina.php"`,
   un campo nascosto con l'id e un pulsante "Elimina"
   (con `onsubmit="return confirm('Eliminare il libro?')"`).
2. `elimina.php` accetta solo POST, esegue il DELETE e torna al catalogo.
3. Prova a eliminare "Il nome della rosa": il database lo rifiuta, perché ha dei prestiti.
   Intercetta l'errore con `try / catch (PDOException $e)` e mostra un messaggio leggibile
   invece della pagina di errore.
