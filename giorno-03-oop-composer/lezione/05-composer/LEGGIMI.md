# 05 - Namespace, autoload e Composer

## Installare Composer (una volta sola)
- **Windows**: https://getcomposer.org/download → `Composer-Setup.exe`.
  Nella schermata "Developer mode" **non** spuntare niente. Trova da solo `C:\php\php.exe`
  e aggiunge Composer al PATH: non serve fare altro.
- **Mac**: `brew install composer`.

Chiudi e riapri VS Code, poi: `composer -V`.

## Cosa fa Composer
1. **Scarica librerie** di altri (con `composer require nome/pacchetto`) nella cartella `vendor`.
   Laravel stesso si installa con Composer (giorno 8).
2. **Carica le nostre classi da solo** (autoload): niente più `require` per ogni file.

## Provare questo esempio
Dalla cartella `05-composer`:
```bash
composer dump-autoload    # crea la cartella vendor con il caricatore automatico
php esegui.php
```

## Come funziona
- `composer.json` dice: le classi il cui namespace inizia con `App\` stanno nella cartella `src/`.
- La classe `App\Modelli\Libro` sta quindi nel file `src/Modelli/Libro.php`.
  Namespace = cartelle, nome della classe = nome del file (con la maiuscola).
- `esegui.php` include solo `vendor/autoload.php`; ogni classe viene caricata
  la prima volta che si usa.

Dopo aver aggiunto una **cartella** nuova sotto `src` non serve fare niente.
Dopo aver modificato `composer.json` si riesegue `composer dump-autoload`.

La cartella `vendor` non si modifica e non va su Git: si ricrea con `composer install`.
