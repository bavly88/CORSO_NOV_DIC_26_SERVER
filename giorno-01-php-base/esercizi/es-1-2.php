
<?php
$annoCorrente = (int)date('Y');
$annoNascita = 1996;
$residente = true;

$eta = $annoCorrente - $annoNascita;

[$tessera, $costoBase] = match (true) {
    $eta <= 13 => ["Ragazzi", 0.00],
    $eta >= 14 && $eta <= 25 => ["Giovani", 5.00],
    $eta >= 26 && $eta <= 64 => ["Adulti", 15.00],
    default => ["Senior", 8.00],
};

$costoFinale = $residente ? $costoBase * 0.80 : $costoBase;

$stringaCosto = ($costoFinale == 0) ? "costo gratuito" : "costo " . number_format($costoFinale, 2, ',', '.') . " €";

echo "Età $eta - tessera $tessera - $stringaCosto\n";

?>


