<?php
// Esercizio 6.5 - Eliminare un libro
require __DIR__ . '/db.php';

// solo POST: un link (GET) non deve mai cancellare dati
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);   // metodo non permesso
    exit('Usa il pulsante Elimina.');
}

$id = (int) ($_POST['id'] ?? 0);

try {
    db()->prepare("DELETE FROM libri WHERE id = ?")->execute([$id]);
    header('Location: index.php');
} catch (PDOException $e) {
    // errore 1451: ci sono prestiti collegati (chiave esterna RESTRICT)
    header('Location: libro.php?id=' . $id . '&errore=1');
}
exit;
