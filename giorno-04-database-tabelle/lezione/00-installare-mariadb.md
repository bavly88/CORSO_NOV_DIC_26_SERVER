# Installare MariaDB e HeidiSQL

La guida completa è nella dispensa "Installare l'ambiente del corso". Passi essenziali:

## Windows
1. https://mariadb.org/download → **MariaDB Server 11.8** (LTS), Windows x86_64, MSI.
2. Nell'installazione:
   - imposta una password per l'utente **root** e annotala;
   - lascia la porta **3306** (se è occupata da un vecchio MySQL, va fermato:
     Servizi di Windows → MySQL80 → Arresta, Tipo di avvio "Disabilitato");
   - lascia spuntato **HeidiSQL**: è il programma con cui vediamo e interroghiamo il database.
3. Apri HeidiSQL → Nuova sessione → utente `root`, la tua password → Apri.

## Mac
`brew install mariadb@11.8`, poi `brew services start mariadb@11.8`.
Come programma grafico: **DBeaver** (https://dbeaver.io), connessione di tipo MariaDB.

## L'utente del corso
In HeidiSQL, scheda **Query**, esegui (F9):
```sql
CREATE USER 'corso'@'localhost' IDENTIFIED BY 'corso';
GRANT ALL PRIVILEGES ON *.* TO 'corso'@'localhost';
```
Da qui in avanti ci si collega sempre con utente `corso`, password `corso`.

## Usare HeidiSQL
- A sinistra: i database e le loro tabelle. Dopo aver creato qualcosa con una query,
  **F5** aggiorna l'elenco.
- Scheda **Query**: si scrive SQL e si esegue con **F9** (tutto) o **Ctrl+F9** (solo la riga selezionata).
- **File > Carica file SQL**: apre uno script `.sql` nella scheda Query; poi F9.
