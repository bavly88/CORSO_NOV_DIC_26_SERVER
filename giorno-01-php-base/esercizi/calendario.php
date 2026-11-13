<?php
$giorniApertura = 0;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>IL Calendario delle aperture</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 60%; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totale { font-weight: bold; font-size: 1.1em; }
    </style>
</head>
<body>

    <h2>Calendario delle prossime due settimane</h2>

    <table>
        <tr>
            <th>Data</th>
            <th>Giorno</th>
            <th>Orario</th>
        </tr>
        <?php

        for ($i = 0; $i <= 13; $i++) {
            $timestamp = strtotime("+$i days");
            
            $giornoNumero = (int)date('N', $timestamp);
            
            if ($giornoNumero === 7) {
                continue;
            }
            
            $giorniApertura++;
            
            $dataFormattata = date('d/m/Y', $timestamp);
            
            $giornoNome = match ($giornoNumero) {
                1 => 'Lunedì',
                2 => 'Martedì',
                3 => 'Mercoledì',
                4 => 'Giovedì',
                5 => 'Venerdì',
                6 => 'Sabato',
                default => '',
            };
            
            $orario = ($giornoNumero === 6) ? 'chiude alle 13:00' : '9:00 - 19:00';
            
            echo "<tr>";
            echo "<td>$dataFormattata</td>";
            echo "<td>$giornoNome</td>";
            echo "<td>$orario</td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <p class="totale">Giorni di apertura: <?php echo $giorniApertura; ?></p>

</body>
</html>