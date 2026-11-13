<?php

echo "10" + "5";

echo "<br>";

echo "10" . "5";

echo "<br>";

echo 7 / 2;

echo "<br>";

echo intdiv(7, 2);

echo "<br>";

echo 7 % 2;

echo "<br>";

var_dump("1" == "01");

echo "<br>";

var_dump("1" === "01");

echo "<br>";

var_dump("abc" == 0);

echo "<br>";

// Spiegazione: Il classico problema dei numeri in virgola mobile (floating-point precision). 0.1 + 0.2 non fa esattamente 0.3 a livello binario, ma 0.30000000000000004.
var_dump(0.1 + 0.2 == 0.3);

echo "<br>";

$nome = null;

echo $nome ?? " mbappe" ;

echo "<br>";

$autore = "Calvino";

echo 'Autore: $autore';

echo "<br>";

echo "Autore: $autore";

echo "<br>";

// 13. Output: 8
// Spiegazione: PHP cerca di convertire la stringa "5 mele" in un numero. Legge la parte iniziale numerica ("5") e la somma a 3, ignorando il testo successivo (generando eventualmente un Notice nelle versioni con error reporting attivo).
echo "5 mele" + 3;

echo "<br>";

// 14. Output: 3 (con un Warning/Notice)
// Spiegazione: La stringa "mele" non inizia con un numero, quindi viene convertita in 0. Il risultato della somma è 0 + 3 = 3.
echo "mele" + 3;
?>