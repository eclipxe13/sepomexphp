# eclipxe/sepomexphp changelog

**This document is in spanish**

## Version 3.0.0 2022-07-15

No hay una guía de migración porque el proyecto cambió drásticamente.
El mejor consejo es reimplementar la librería en esta nueva versión.

### Cambios para usuarios

- El namespace principal del proyecto cambia de `SepomexPhp` a `Eclipxe\SepomexPhp`.
- La versión mínima requerida es PHP versión 7.4. Vea <https://www.php.net/supported-versions.php>.

### Cambios para implementadores

- El método `DataGatewayInterface::getZipCodeData()` regresa un arreglo vacío en lugar de nulo cuando no existen resultados.

### Descarga de catálogo

El origen de los datos estaba disponible desde <http://www.correosdemexico.gob.mx/datosabiertos/cp/cpdescarga.txt>,
sin embargo, este origen no se ha actualizado. SEPOMEX ahora permite la descarga a través de una aplicación web,
por lo que tuvo que implementar un *scraper* para obtener el catálogo actualizado desde 
<https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx>.

Se creó el script `scripts/download.php` para descargar y extraer la base de datos de SEPOMEX.

### Desarrollo

- Se elimina la dependencia a phplint.
- Se actualiza la versión de PHPUnit a 9.5.
- Se migran las herramientas de desarrollo de `composer` a `phive`.
- El proyecto ahora se construye en GitHub Workflows en lugar de Travis CI. ¡Gracias Travis CI!.

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
