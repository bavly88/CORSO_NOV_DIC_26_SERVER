# Giorno 7 – Esercizi del pomeriggio (sessioni, login, sicurezza, upload)

Copia la cartella `partenza` e rinominala `catalogo`: è il catalogo completo del giorno 6,
con in `db.php` le funzioni della lezione di oggi (`session_start`, `redirect`,
`utenteCorrente`, `richiediLogin`, `csrfCampo`, `csrfVerifica`).
Prima di iniziare esegui `00-utenti.sql` in HeidiSQL (crea le tabelle `utenti` e `commenti`
e la colonna `libri.copertina`).

---

## 7.1 – I preferiti in sessione (30 minuti)
1. Nel dettaglio del libro, un form POST con il pulsante "Aggiungi ai preferiti":
   aggiunge l'id del libro a `$_SESSION['preferiti']` (senza doppioni) e torna al dettaglio.
2. Pagina `preferiti.php`: l'elenco dei titoli dei libri preferiti (una query per ogni id,
   con `prepare`), più un pulsante "Svuota".
3. Nella testata, il link "Preferiti (3)" con il numero dei preferiti.

## 7.2 – Login nel catalogo (60 minuti)
1. Copia nel catalogo `registrazione.php`, `login.php`, `logout.php` della lezione,
   e adatta la testata: Accedi e Registrati per chi non è loggato, il nome e "Esci" per chi lo è.
2. Le pagine `nuovo.php`, `modifica.php`, `elimina.php`, `nuovo-autore.php` sono riservate:
   `richiediLogin()` come prima istruzione dopo il `require`.
3. Nel dettaglio del libro, i link Modifica ed Elimina compaiono solo a chi è loggato.
4. Prova ad aprire `nuovo.php` senza login: dove arrivi?

## 7.3 – Token CSRF in tutti i form POST (30 minuti)
1. In ogni form con `method="post"` aggiungi `<?= csrfCampo() ?>`.
2. In ogni pagina che riceve un POST, `csrfVerifica()` come prima istruzione del blocco POST.
3. Verifica: con F12 cancella il campo nascosto `csrf` da un form e invialo. Che codice ricevi?

## 7.4 – Messaggi flash (30 minuti)
Dopo un salvataggio la pagina dice "Libro salvato." una volta sola.
1. Prima del redirect: `$_SESSION['flash'] = 'Libro salvato.';`.
2. Nella testata: se `$_SESSION['flash']` esiste, mostralo in un paragrafo e poi
   cancellalo con `unset($_SESSION['flash'])`.
3. Usalo dopo nuovo libro, modifica, eliminazione e nuovo autore.

## 7.5 – La copertina nel catalogo (40 minuti)
1. Nel dettaglio del libro, per chi è loggato, un form per caricare la copertina,
   con le quattro regole della lezione (`upload.php`). Il file va in `uploads/`,
   il nome nella colonna `libri.copertina`.
2. Il dettaglio mostra la copertina, se c'è.
3. Prova a caricare un file di testo rinominato in `.jpg`: quale controllo lo blocca?

## 7.6 – Caccia alle vulnerabilità (20 minuti)
Apri `vulnerabile.php` e, **senza eseguirlo**, scrivi in un commento in cima al file:
quali problemi ha, quale attacco permette ciascuno e come si corregge.
Poi correggilo.
