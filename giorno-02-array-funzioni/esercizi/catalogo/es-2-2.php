<?php
declare(strict_types=1);

function iniziali(string $autore): string {
    $parole = explode(" ", $autore);
    $risultato = "";
    
    foreach ($parole as $parola) {
        if ($parola !== "") {
            $risultato .= $parola[0] . ".";
        }
    }
    
    return $risultato;
}

echo "--- Test Passo 1 (iniziali) --- \n";
echo iniziali("Italo Calvino") . "\n";  
echo iniziali("Umberto Eco") . "\n";     
echo "\n";


function stelle(int $voto, int $massimo = 5): string {
    $piene = str_repeat('★', $voto);
    $vuote = str_repeat('☆', $massimo - $voto);
    
    return $piene . $vuote;
}

echo "--- Test Passo 2 (stelle) --- \n";
echo stelle(3) . "\n";       
echo stelle(7, 10) . "\n";   
echo "\n";


function euro(float $importo): string {
    return number_format($importo, 2, ',', '.') . ' €';
}

echo "--- Test Passo 3 (euro) --- \n";
echo euro(1234.5) . "\n";   
echo euro(8) . "\n";        
echo "\n";


function slug(string $titolo): string {
    
    $testo = strtolower($titolo);
    
    
    $testo = strtr($testo, [
        'à' => 'a', 'è' => 'e', 'é' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
        'à' => 'a', 'è' => 'e', 'é' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u'
    ]);
    
    $testo = str_replace([' ', "'", '"'], '-', $testo);
    
    return $testo;
}

echo "--- Test Passo 4 (slug) --- \n";
echo slug("Se questo è un uomo") . "\n";   // se-questo-e-un-uomo
echo slug("L'isola di Arturo") . "\n";     // l-isola-di-arturo
echo "\n";


echo "--- Test Passo 5 (Catalogo libri) --- \n";
$libri = require __DIR__ . '/libri.php';

foreach ($libri as $libro) {
    $inizialiAutore = iniziali($libro['autore']);
    $titoloLibro = $libro['titolo'];
    $slugTitolo = slug($titoloLibro);
    
    echo "$inizialiAutore - $titoloLibro - $slugTitolo\n";
}

/*
 * Se nel catalogo sono presenti lettere accentate maiuscole (es. "É" o "È") 
 * oppure simboli di punteggiatura particolari (come virgole `,`, punti `.` o punti interrogativi `?`),
 * la funzione base potrebbe non convertirli correttamente perché:
 * 1. `strtolower` non copre sempre tutte le maiuscole accentate a seconda della codifica o configurazione.
 * 2. L'array di `strtr` nel Passo 4 mappava solo le minuscole. Per risolverlo del tutto, 
 *    conviene aggiungere anche le vocali accentate maiuscole (es. 'È' => 'e', 'É' => 'e') 
 *    e gestire eventuali caratteri di punteggiatura aggiuntivi in `str_replace`.
 */
echo "\n";

/*
 * SENZA declare(strict_types=1); :
 * 
 * 1. echo stelle("3") . "\n"; 
 *    -> Funziona comunque grazie al "coercing" (conversione automatica dei tipi) di PHP: 
 *       la stringa "3" viene convertita automaticamente in intero (3).
 * 
 * 2. echo stelle(3.7) . "\n"; 
 *    -> PHP converte il float 3.7 in intero troncando la parte decimale (diventa 3), 
 *       stampando le stelle senza generare blocchi, ma emettendo eventualmente un avviso 
 *       nelle versioni più recenti di PHP riguardo alla perdita di dati.
 * 
 * 3. echo stelle("tre") . "\n"; 
 *    -> Solleva un errore critico (Type Error / Notice a seconda della versione), 
 *       perché una stringa alfabetica non può essere convertita numericamente in modo sicuro.
 * 
 * CON declare(strict_types=1); :
 * 
 * Inserendo `declare(strict_types=1);` in cima al file, PHP diventa rigoroso sui tipi dichiarati 
 * nella firma delle funzioni. Di conseguenza:
 * - `stelle("3")` e `stelle(3.7)` generano un errore irreversibile di tipo (Fatal Error / TypeError), 
 *   perché ci si attende rigorosamente un `int` e viene passato rispettivamente una stringa e un float.
 */

// Decommenta una riga alla volta per testare l'effetto con strict_types attivo:
// echo stelle("3") . "\n";
// echo stelle(3.7) . "\n";
// echo stelle("tre") . "\n";