<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Dati reali, gli stessi del database "biblioteca" usato nella parte SQL.
 */
class BookshelfSeeder extends Seeder
{
    public function run(): void
    {
        $genres = collect(['Romanzo', 'Racconti', 'Saggio', 'Giallo', 'Poesia'])
            ->mapWithKeys(fn ($name) => [$name => Genre::create(['name' => $name])]);

        $authors = [
            'calvino'    => Author::create(['first_name' => 'Italo', 'last_name' => 'Calvino', 'nationality' => 'italiana', 'birth_year' => 1923]),
            'morante'    => Author::create(['first_name' => 'Elsa', 'last_name' => 'Morante', 'nationality' => 'italiana', 'birth_year' => 1912]),
            'levi'       => Author::create(['first_name' => 'Primo', 'last_name' => 'Levi', 'nationality' => 'italiana', 'birth_year' => 1919]),
            'eco'        => Author::create(['first_name' => 'Umberto', 'last_name' => 'Eco', 'nationality' => 'italiana', 'birth_year' => 1932]),
            'ginzburg'   => Author::create(['first_name' => 'Natalia', 'last_name' => 'Ginzburg', 'nationality' => 'italiana', 'birth_year' => 1916]),
            'sciascia'   => Author::create(['first_name' => 'Leonardo', 'last_name' => 'Sciascia', 'nationality' => 'italiana', 'birth_year' => 1921]),
            'fruttero'   => Author::create(['first_name' => 'Carlo', 'last_name' => 'Fruttero', 'nationality' => 'italiana', 'birth_year' => 1926]),
            'lucentini'  => Author::create(['first_name' => 'Franco', 'last_name' => 'Lucentini', 'nationality' => 'italiana', 'birth_year' => 1920]),
            'woolf'      => Author::create(['first_name' => 'Virginia', 'last_name' => 'Woolf', 'nationality' => 'britannica', 'birth_year' => 1882]),
        ];

        // [titolo, anno, pagine, genere, [autori]]
        $books = [
            ['Il barone rampante', 1957, 270, 'Romanzo', ['calvino']],
            ["Le città invisibili", 1972, 168, 'Romanzo', ['calvino']],
            ['Marcovaldo', 1963, 160, 'Racconti', ['calvino']],
            ['La storia', 1974, 660, 'Romanzo', ['morante']],
            ["L'isola di Arturo", 1957, 400, 'Romanzo', ['morante']],
            ['Il sistema periodico', 1975, 240, 'Racconti', ['levi']],
            ["Se questo è un uomo", 1947, 220, 'Saggio', ['levi']],
            ['Il nome della rosa', 1980, 503, 'Giallo', ['eco']],
            ['Il pendolo di Foucault', 1988, 509, 'Romanzo', ['eco']],
            ['Lessico famigliare', 1963, 230, 'Romanzo', ['ginzburg']],
            ['Il giorno della civetta', 1961, 130, 'Giallo', ['sciascia']],
            ['A ciascuno il suo', 1966, 160, 'Giallo', ['sciascia']],
            ['La donna della domenica', 1972, 450, 'Giallo', ['fruttero', 'lucentini']],
            ['Gita al faro', 1927, 230, 'Romanzo', ['woolf']],
            ["Una stanza tutta per sé", 1929, 160, 'Saggio', ['woolf']],
        ];

        // I libri reali appartengono all'utente demo, cosi' si possono modificare dopo il login
        $ownerId = User::where('email', 'demo@example.com')->value('id');

        foreach ($books as [$title, $year, $pages, $genre, $authorKeys]) {
            $book = new Book([
                'title'    => $title,
                'year'     => $year,
                'pages'    => $pages,
                'genre_id' => $genres[$genre]->id,
            ]);
            $book->user_id = $ownerId;   // user_id non e' in $fillable: si assegna a mano
            $book->save();

            // attach() inserisce le righe nella tabella pivot author_book
            $book->authors()->attach(
                collect($authorKeys)->map(fn ($k) => $authors[$k]->id)
            );
        }
    }
}
