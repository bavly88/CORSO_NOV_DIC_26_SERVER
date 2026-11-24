<?php
// 03 - LOGOUT: con POST e token CSRF, perché modifica lo stato (come un'eliminazione)
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfVerifica();
    $_SESSION = [];
    session_destroy();
}
redirect('index.php');
