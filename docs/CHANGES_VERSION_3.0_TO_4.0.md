# Cambios principales de la versión 3.0 a 4.0

En esta actualización, se supone que es posible reutilizar los datos del formulario indefinidamente, por lo tanto,
no sería necesario utilizar una herramienta tan completa como *Symfony Browser Kit* y simplemente se puede generar
la descarga utilizando un cliente HTTP.


## La clase `Downloader` se renombró a `SymfonyDownloader`

Sustituya en su proyecto la importación de la clase, por ejemplo:

```diff
- use Eclipxe\SepomexPhp\Downloader\Downloader;
+ use Eclipxe\SepomexPhp\Downloader\SymfonyDownloader as Downloader;
```

La clase `SymfonyDownloader` se comporta como un navegador que entra la página del recurso,
selecciona la opción deseada y envía el formulario. Por lo anterior, es la opción que mejor
funcione a largo plazo.

También se pasó la dependencia de la librería `symfony/browser-kit` a una sugerencia,
debido a que se puede utilizar cualquier implementación.

## Se agregó `GuzzleDownloader`

La clase `GuzzleDownloader` permite hacer la descarga del recurso público pero utilizando
[Guzzle](https://github.com/guzzle/guzzle).

A diferencia de la clase `SymfonyDownloader`, no interpreta el formulario y utiliza datos fijos.

## Se agregó `PhpStreamsDownloader`

La clase `PhpStreamsDownloader` permite hacer la descarga del recurso público, pero utilizando
funciones de PHP. No necesita ninguna dependencia externa, pero en algunos entornos restringidos
podría llegar a fallar.

A diferencia de la clase `SymfonyDownloader`, no interpreta el formulario y utiliza datos fijos.
