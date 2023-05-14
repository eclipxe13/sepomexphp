# eclipxe/sepomexphp changelog

**This document is in spanish**

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
