<?php
/*
 * 02 - PREPARED STATEMENT: query con valori che arrivano dall'esterno
 * Esecuzione:  php 02-prepared.php
 */
$config = require __DIR__ . '/config.php';
$pdo = new PDO(
    "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4",
    $config['username'],
    $config['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

// Immaginiamo che questo valore arrivi da un form o dall'indirizzo ($_GET['id'])
$id = 8;

// MAI così: il valore incollato dentro la stringa SQL
//   $pdo->query("SELECT * FROM libri WHERE id = $id");
// Se il valore fosse "8 OR 1=1", la query restituirebbe TUTTI i libri: SQL injection (giorno 7).

// GIUSTO: prepare con un segnaposto ?, poi execute con i valori.
// Il database riceve query e valori SEPARATI: il valore non può cambiare la query.
$stmt = $pdo->prepare("SELECT titolo, anno FROM libri WHERE id = ?");
$stmt->execute([$id]);
$libro = $stmt->fetch();   // false se non c'è nessuna riga
print_r($libro);

// Segnaposto con nome: più leggibili quando sono tanti
$stmt = $pdo->prepare("SELECT titolo, anno FROM libri WHERE anno BETWEEN :da AND :a ORDER BY anno");
$stmt->execute(['da' => 1960, 'a' => 1970]);
$results = $stmt->fetchAll();
foreach ($results as $l) {
    echo "{$l['anno']} {$l['titolo']}\n";
}

// LIKE: il % fa parte del VALORE, non della query
$cerca = "rosa";
$stmt = $pdo->prepare("SELECT titolo FROM libri WHERE titolo LIKE ?");
$stmt->execute(['%' . $cerca . '%']);
print_r($stmt->fetchAll());

//POTEVAMO ANCHE SCRIVERE :cerca invece del ?
// $stmt = $pdo->prepare("SELECT titolo FROM libri WHERE titolo LIKE :cerca");
// $stmt->execute( ['cerca' => '%' . $cerca . '%']);
// print_r($stmt->fetchAll());

// INSERT: lastInsertId() restituisce l'id assegnato dal database
$stmt = $pdo->prepare("INSERT INTO generi (nome) VALUES (?)");
$stmt->execute(['Fantascienza']);
echo "Nuovo genere con id " . $pdo->lastInsertId() . "\n";

// Pulizia, così l'esempio si può rieseguire
$pdo->prepare("DELETE FROM generi WHERE nome = ?")->execute(['Fantascienza']);
