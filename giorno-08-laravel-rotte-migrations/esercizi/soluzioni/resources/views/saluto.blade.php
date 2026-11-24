<!doctype html>
<html lang="it">
<head><meta charset="utf-8"><title>Saluto</title></head>
<body>
    <h1>Ciao {{ $nome }}</h1>
    <p>Oggi è il {{ date('d/m/Y') }}.</p>
    <p><a href="{{ route('info') }}">Informazioni</a></p>
    <p><a href="{{ route('anno', 1980) }}">Libri del 1980</a></p>
</body>
</html>
