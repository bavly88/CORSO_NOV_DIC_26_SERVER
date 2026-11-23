<?php
/*
 * 02 - PASSWORD: password_hash e password_verify
 * Esecuzione:  php 02-password.php
 *
 * Le password non si salvano MAI in chiaro: se qualcuno ruba il database,
 * non deve poter leggere le password degli utenti.
 */
$password = "segreta123";

// password_hash: trasforma la password in un HASH, una stringa da cui non si torna indietro.
// Contiene anche un "sale" casuale: la stessa password dà ogni volta un hash diverso.
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash . "\n";
echo password_hash($password, PASSWORD_DEFAULT) . "\n";   // diverso dal primo

// password_verify: controlla se una password corrisponde a un hash
var_dump(password_verify("segreta123", $hash));   // true
var_dump(password_verify("sbagliata", $hash));    // false

// Nel database si salva solo $hash (colonna password_hash, VARCHAR(255)).
// MAI usare md5 o sha1 per le password: sono velocissimi da provare a forza bruta.
