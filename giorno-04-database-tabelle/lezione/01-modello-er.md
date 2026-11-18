# 01 - Dal problema alle tabelle

## Il problema
Bookshelf deve ricordare: i **libri** (titolo, anno, pagine), il loro **genere**,
i loro **autori**. Un libro può avere più autori ("La donna della domenica":
Fruttero e Lucentini).

## Perché non una tabella sola

| titolo | anno | genere | autori |
|---|---|---|---|
| Il barone rampante | 1957 | Romanzo | Italo Calvino |
| Le città invisibili | 1972 | Romanzo | Italo Calvino |
| La donna della domenica | 1972 | Giallo | Carlo Fruttero, Franco Lucentini |

Problemi:
- "Italo Calvino" è scritto due volte: se lo correggo in una riga, l'altra resta sbagliata;
- la cella "Carlo Fruttero, Franco Lucentini" contiene **due** valori: non posso cercare
  i libri di Lucentini in modo affidabile;
- per aggiungere un autore senza libri non c'è posto.

**Regola della normalizzazione**: ogni informazione si scrive in **un solo posto**,
e ogni cella contiene **un solo valore**.

## Entità e relazioni (modello E/R)

```
GENERI 1 ──── N LIBRI N ──── N AUTORI
```

- Un genere ha molti libri, un libro ha un solo genere → **uno a molti (1-N)**.
- Un libro ha più autori, un autore scrive più libri → **molti a molti (N-N)**.

## Dalle relazioni alle tabelle

| Relazione | Come si traduce |
|---|---|
| 1-N | una **chiave esterna** nella tabella del lato N: `libri.genere_id` |
| N-N | una **tabella ponte** con le due chiavi esterne: `libri_autori(libro_id, autore_id)` |

- **Chiave primaria** (`id`): il numero che identifica ogni riga. Non cambia mai.
- **Chiave esterna** (`genere_id`): contiene l'id di una riga di un'altra tabella.
  Il database rifiuta un `genere_id` che non esiste.

```
generi            libri                      libri_autori          autori
------            -----                      ------------          ------
id  ◄──────────── genere_id                  libro_id  ──────────► (libri.id)
nome              id  ◄───────────────────── autore_id ──────────► id
                  titolo, anno, pagine                              nome, cognome
```

Nel progetto Laravel le stesse tabelle avranno nomi inglesi:
`genres`, `books`, `authors`, `author_book`.
