# eclipxe/sepomexphp changelog

**This document is in spanish**

## Mantenimiento 2024-10-23

Se da mantenimiento a los archivos de documentación y de desarrollo del proyecto:

- Se actualiza el archivo de licencia a 2024.
- Se actualiza la configuración de `php-cs-fixer` por que la regla `function_typehint_space` fue deprecada.
- Se agrega la compatibilidad con Symfony 7.x en desarrollo.
- En los flujos de trabajo de GitHub:
  - Se agrega PHP 8.3 a la matriz de pruebas.
  - Se usa PHP 8.3 para los trabajos.
  - Se actualizan las acciones de GitHub a la versión 4.
  - Se cambia la variable `php-version` a singular.
  - Se agrega la configuración extensiones al ejecutar el trabajo `phpunit` pues falla en `nektos/act`.
- Se actualizan las herramientas de desarrollo.

## Versión 4.0.0 2023-06-30

El proyecto tiene cambios menores en la descarga, pero que rompen la compatibilidad.
Puede ver la guía de actualización en el archivo [CHANGES_VERSION_3.0_TO_4.0.md](CHANGES_VERSION_3.0_TO_4.0.md).

En esta actualización, se supone que es posible reutilizar los datos del formulario indefinidamente, por lo tanto,
no sería necesario utilizar una herramienta tan completa como *Symfony Browser Kit* y simplemente se puede generar
la descarga utilizando un cliente HTTP.

### Cambios para usuarios

- La clase `Downloader` se renombró a `SymfonyDownloader`.
- Se agregó `GuzzleDownloader` que también permite hacer la descarga del recurso público.
- Se agregó `PhpStreamsDownloader` que también permite hacer la descarga del recurso público sin dependencias.
- Ya no es necesario instalar forzosamente `symfony/browser-kit`.
- El proyecto sugiere `symfony/browser-kit` y `guzzlehttp/guzzle`, haciendo opcional su instalación.

## Version 3.0.0 2023-05-13

El proyecto cambió drásticamente. El mejor consejo es volver a implementar la librería en esta nueva versión.
Puede ver la guía de implementación en el archivo [CHANGES_VERSION_2.0_TO_3.0.md](CHANGES_VERSION_2.0_TO_3.0.md).

### Cambios para usuarios

- El namespace principal del proyecto cambia de `SepomexPhp` a `Eclipxe\SepomexPhp`.
- La versión mínima requerida es PHP versión 8.1. Vea <https://www.php.net/supported-versions.php>.
- Se creó el script `scripts/download.php` para descargar y extraer la base de datos de SEPOMEX.

### Cambios para implementadores

- El método `DataGatewayInterface::getZipCodeData()` regresa un arreglo vacío en lugar de nulo cuando no existen resultados.

### Desarrollo

- Se elimina la dependencia a PHPLint.
- Se actualiza la versión de PHPUnit a 9.5.
- Se migran las herramientas de desarrollo de `composer` a `phive`.
- El proyecto ahora se construye en GitHub Workflows en lugar de Travis CI. ¡Gracias Travis CI!.
- El proyecto ahora se analiza en SonarCloud en lugar de Scrutinizer-CI. ¡Gracias Scrutinizer-CI!.

## Version 2.0.0 2018-03-09

- Drop compatibility with PHP 5.6, minimum is PHP 7.0
- Use typehints and strict mode
- Data classes are now immutable (value objects)
- Introduce collections of cities and locations
- Use traits for common properties
- Add tests for PdoImporter using an extract of the raw data
- Do not remove the raw table, just delete all contents

## Version 1.1.1 2018-02-02

- Make use of phpstan, include in travis and in CONTRIBUTING.md
- Fix docblocks according to phpstan and scrutinizer
- Add gitter to README.md
- Check compatibility with PHP 7.2, remove HHVM
- update composer.json, .travis.yml, .scrutinizer.yml

## Version 1.1.0 2017-01-26

- Rename interface `DataGateway` to `DataGatewayInterface`
- Travis integration and php 7.1
- Code comments cleanup

## Version 1.0.1 2017-01-26

- Document changes to data source license "uso libre mx"
- Move assets to `/assets/` instead of `/test/assets/`
- Apply code style
- Add badges to README
- Add more files to make it a first class library

## Version 1.0.0

- First release
