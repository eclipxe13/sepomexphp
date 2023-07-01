# eclipxe\sepomexphp - Servicio Postal Mexicano PHP Library

[![Source][badge-source]][source]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Total Downloads][badge-downloads]][downloads]

This library is an unofficial version for the Mexican SEPOMEX data.

Some parts of the project are in Spanish since the main consumers of this library are mexicans.
Anyhow, all the database, code and other information is in english.

## Installation

Install using composer, no other methods are recommended!

```
composer require eclipxe/sepomexphp
```

## Usage

Using a Sqlite3 database file created by the library:

```php
<?php
/** @var string $databaseFilename set the database file location */
$sepomex = \Eclipxe\SepomexPhp\SepomexPhp::createForDatabaseFile($databaseFilename);

// query a zip code
$zipcode = $sepomex->getZipCodeData('86100');
```

Using your own PDO connection:

```php
<?php
/** @var string $pdoString set the database connection using Pdo */
$pdoString = "...";

// create the SepomexPhp Object
$sepomex = new \Eclipxe\SepomexPhp\SepomexPhp(
    new \Eclipxe\SepomexPhp\PdoDataGateway\PdoDataGateway(
        new \PDO($pdoString)
    )
);

// query a zip code
$zipcode = $sepomex->getZipCodeData((int) $argv[1]);
```

Also, check the `zipcode-info.php` script. And `ZipCodeDataTest.php` test.

If you only want to download the source file from SEPOMEX, check script file `scripts/download.php`.

```php
<?php
/**
 * @var string $destinationFile is the path where the destination file will be located. 
 */
$downloader = new \Eclipxe\SepomexPhp\Downloader\SymfonyDownloader();
printf("Download from %s to %s\n", $downloader::LINK, $destinationFile);
$downloader->downloadTo($destinationFile);
```

It is possible to use your own downloader, just implement the interface `DownloaderInterface`.

The project provides the following implementations:

- `SymfonyDownloader`: It uses *Symfony Browser Kit* to perform the download (recommended).
- `GuzzleDownloader`: Uses *Guzzle* and fixed data to perform the download. 
- `PhpStreamsDownloader`: Uses plain PHP functions and fixed data to perform the download. 

If you want to import the source file from SEPOMEX into your own SQLite3 database, check `create-sqlite-from-raw.php`.

```php
<?php
/**
 * @var PDO $pdo The PDO connection to your database.
 * @var string $sourceFile The path to the SEPOMEX database in TXT format. 
 */
$importer = new \Eclipxe\SepomexPhp\Importer\PdoImporter($pdo);
// drop tables if existed, create tables.
$importer->createStruct();
// perform data import
$importer->import($sourceFile);
```

Do you have your own dataset of Sepomex? Then you can extend this library,
just create `DataGatewayInterface` that implements the methods and get the data from anywhere.

## About the SEPOMEX information (as of 2018-02-02)

Sepomex distribute its database of postal codes with a very restrictive clause on its first line:

> *El Catálogo Nacional de Códigos Postales, es elaborado por Correos de México y se proporciona en forma gratuita para uso particular, 
> no estando permitida su comercialización, total o parcial, ni su distribución a terceros bajo ningún concepto.*

That means:

* The data is distributed only for personal use.
* Cannot redistribute the information (total or partial) to anyone (insert a big WTF! here).
* Cannot create any profit of the information.

Anyhow, the data source has been released in 
<https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales/resource/2c5c36de-ffed-4dc6-9beb-66369db3a622>
by the Mexican government using a libre license called [LIBRE USO MX](https://datos.gob.mx/libreusomx),
and it removes any restrictions from the disclaimer.

If you run the script `scripts/create-sqlite-from-raw.php` you will create a sqlite database
with the same information but normalized, the script will download the source if it does not exist.

You would find more information about the source raw file inside [docs/DATABASE.md](docs/DATABASE.md) (*spanish*)

## What is working

Right now you can search a mexican zip code, and it will give you the information about:

* The state *Estado* where it is located, like 'Tabasco'
* The district *Delegación/Municipio* where it is located, like 'Centro'
* A list of locations *Colonias*, each location contains:
    * Name of the location, like '1 de mayo'
    * Type of the location, like 'Colonia' or 'Unidad habitacional'
    * City Name, like 'Villahermosa'

The city is located under the location entity because the same zip code can include some places inside the city
and also some places outside the city. Yes, this is how it works in Mexico.

## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details
and don't forget to take a look the [TODO][] and [CHANGELOG][] files.

## License

The `eclipxe\sepomexphp` library is copyright © [Carlos C Soto](https://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

## Data source

"Tabla de Códigos Postales y asentamientos humanos" published by "Correos de México".
Getting from:
- Since 2017-01-25 <http://www.correosdemexico.gob.mx/datosabiertos/cp/cpdescarga.txt>
- Since 2023-05-13 <https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx>

[contributing]: https://github.com/eclipxe13/sepomexphp/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/eclipxe13/sepomexphp/blob/master/docs/CHANGELOG.md
[todo]: https://github.com/eclipxe13/sepomexphp/blob/master/docs/TODO.md

[source]: https://github.com/eclipxe13/sepomexphp
[php-version]: https://packagist.org/packages/eclipxe/sepomexphp
[release]: https://github.com/eclipxe13/sepomexphp/releases
[license]: https://github.com/eclipxe13/sepomexphp/blob/master/LICENSE
[build]: https://github.com/eclipxe13/sepomexphp/actions/workflows/build.yml?query=branch:master
[reliability]:https://sonarcloud.io/component_measures?id=eclipxe13_sepomexphp&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=eclipxe13_sepomexphp&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=eclipxe13_sepomexphp&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=eclipxe13_sepomexphp&resolved=false
[downloads]: https://packagist.org/packages/eclipxe/sepomexphp

[badge-source]: http://img.shields.io/badge/source-eclipxe13/sepomexphp-blue.svg?style=flat-square
[badge-php-version]: https://img.shields.io/packagist/php-v/eclipxe/sepomexphp?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/sepomexphp.svg?style=flat-square
[badge-license]: https://img.shields.io/github/license/eclipxe13/sepomexphp.svg?style=flat-square
[badge-build]: https://img.shields.io/github/actions/workflow/status/eclipxe13/sepomexphp/build.yml?branch=master&style=flat-square
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=eclipxe13_sepomexphp&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=eclipxe13_sepomexphp&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/eclipxe13_sepomexphp/master?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/eclipxe13_sepomexphp/master?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/sepomexphp.svg?style=flat-square
