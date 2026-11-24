# Giorno 9 – Laravel 2: model, relazioni, seeder e factory

Riferimento: dispensa "Laravel - applicazioni web MVC", capitoli 4, 5 e 6.
La cartella `bookshelf/` qui accanto contiene i file come sono **alla fine della mattina**.

| Ora | Passo | Dispensa |
|---|---|---|
| 9:00 | Ripasso: le tabelle create ieri. Cos'è un ORM | 4 |
| 9:10 | `php artisan make:model Genre -f` (poi Author, Book). Il model generato, `#[Fillable]` | 4.1 |
| 9:40 | `php artisan tinker`: creare, leggere, modificare, eliminare | 4.2-4.4 |
| 10:15 | L'accessor `full_name` in Author | 4.5 |
| 10:30 | Pausa | |
| 10:45 | Relazioni 1-N: `hasMany`, `belongsTo`; provarle in tinker | 5.1 |
| 11:15 | Relazione N-N: `belongsToMany`, `attach`, `sync` | 5.2 |
| 11:40 | Lo scope `search` nel model Book | 5.4 |
| 11:55 | Seeder: `make:seeder BookshelfSeeder` (i dati si copiano dalla cartella `bookshelf/`), DatabaseSeeder, factory, `php artisan migrate:fresh --seed` | 6 |
| 12:30 | Margine per domande e ripasso | |

Cosa deve funzionare alla fine della mattina:
```
php artisan migrate:fresh --seed
php artisan tinker
> Book::count()                              // 30
> Book::find(13)->authors->pluck('full_name')  // Carlo Fruttero, Franco Lucentini
> Book::search('calvino')->count()           // 3
```

Tolti rispetto alla dispensa: condizioni sulle relazioni (whereHas, doesntHave, withCount, 5.3).
withCount torna il giorno 10 nella pagina degli autori.

Attenzione: tinker legge le classi solo all'avvio. Dopo aver modificato un model: `exit` e di nuovo `php artisan tinker`.
Nel terminale Git Bash fuori da VS Code, se tinker non mostra il prompt: `winpty php artisan tinker`.
