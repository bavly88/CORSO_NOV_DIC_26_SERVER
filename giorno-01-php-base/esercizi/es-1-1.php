<?php
$cognome = "Calvino";
$genere = "Romanzo";
$anno = 1957;
$numero = 42;

$codiceCognome=strtoupper(substr($cognome,0,3));
$codiceGenere=strtoupper(substr($genere,0,3));
$unioneCodici=$codiceCognome-$codiceGenere-$anno;
echo "il codice unificato è : $unioneCodici"; 


$numeroAggiunto=str_pad($numero,5, "0", STR_PAD_LEFT);
$codiceCompleto=$unioneCodici-$numeroAggiunto;
echo "il codice completo è : $codiceCompleto"; 

$codiceSprintf = sprintf("%s-%s-%d-%05d", $codiceCognome, $codiceGenere, $anno, $numero);
echo "Codice con sprintf : $codiceSprintf\n";

$provaCognomi = ["Calvino", "Eco", "Fo"];
foreach ($provaCognomi as $cog) {
    $sigla = strtoupper(substr($cog, 0, 3));
    echo "Cognome : '$cog' -> Sigla: '$sigla'\n";
}
?>