# SepomexPhp - Servicio postal Mexicano PHP Library

[![Gitter][badge-gitter]][gitter]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![SensioLabsInsight][badge-sensiolabs]][sensiolabs]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

This library is an unofficial version for the Mexicam SEPOMEX data.

Some parts of the project are in Spanish since the main consumers of this library would are mexicans.
Anyhow, all the database, code and other information is in english (that is not my primary language, so forgive me)
 
# Installation

Install using composer, no other methods are recommended!

```
composer require eclipxe/sepomexphp
```

# Usage

This is a basic usage example to 

```php
<?php
// set the database connection using Pdo
$pdostring = "...";

// create the SepomexPhp Object
$sepomex = new \SepomexPhp\SepomexPhp(
    new SepomexPhp\PdoGateway\Gateway(
        new PDO($pdostring)
    )
);

// query a zip code
$zipcode = $sepomex->getZipCodeData((int) $argv[1]);
```

Also, check the `zipcode-info.php` script. and `ZipCodeDataTest.php`

Do you have your own dataset of Sepomex? You can extend this library, just create `DataGatewayInterface` that
implements the methods and get the data from anywhere.

# About the SEPOMEX information (as of 2018-02-02)

Sepomex distribute its database of postal codes with a very restrictive clause on its first line:

>> *El Catálogo Nacional de Códigos Postales, es elaborado por Correos de México y se proporciona en forma gratuita para uso particular,
no estando permitida su comercialización, total o parcial, ni su distribución a terceros bajo ningún concepto.*

That means:

* The data is ditributed only for personal use
* Cannot redistribute the information (total or partial) to anyone (insert a big WTF! here)
* Cannot create any profit of the information

Anyhow, the data source has been released in 
https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales/resource/13887b9a-f770-4276-8b80-f092cd886b44
by the Mexican goverment using a less restrictive license called LIBRE USO MX https://datos.gob.mx/libreusomx and it
allows us to distribute a copy of the original data and also to manipulate it.

If you run the script `scripts/create-sqlite-from-raw.php` you will create a sqlite database
with the same information but normalized, the script will download the source if not exists.

# What is working

Right now you can search a mexican zip code and it will give you the information about:

* The state *Estado* where it is located, like 'Tabasco'
* The district *Delegación/Municipio* where it is located, like 'Centro'
* A list of locations *Colonias*, each location contains:
    * Name of the location, like '1 de mayo'
    * Type of the location, like 'Colonia' or 'Unidad habitacional'
    * City Name, like 'Villahermosa'

The city is located under the location entity because the same zip code can include some places inside the city
and also some places outside the city. Yes, this is how it works in Mexico.

# What is planned

Search from global to specific:

- [ ] Get the full list of states
- [ ] Select a state and get all cities and districts.
- [ ] Select a district and get all locations
- [ ] Select a city and get all locations
- [ ] Select a location and get all zip codes

Other things to do:

- [ ] Create common names, alias or short names for states.
- [ ] Create an API for public access.
- [ ] Create a sepomex.txt with fake information for testing.

# Are you interested on help to this project?

Contributions are welcome! Please read [CONTRIBUTING][] for details.

Lets make this agnostic, a simple library, without depends on any framework, except for an implementation (like an API)

# License

The SepomexPhp library is copyright © [Carlos C Soto](https://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

## Data source

"Tabla de Códigos Postales y asentamientos humanos" published by "Correos de México".
Getting from https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales on 2017-01-25. 

[contributing]: CONTRIBUTING.md
[license]: LICENSE

[source]: https://github.com/eclipxe13/sepomexphp
[gitter]: https://gitter.im/eclipxe13/sepomexphp
[release]: https://github.com/eclipxe13/sepomexphp/releases
[license]: https://github.com/eclipxe13/sepomexphp/blob/master/LICENSE
[build]: https://travis-ci.org/eclipxe13/sepomexphp
[quality]: https://scrutinizer-ci.com/g/eclipxe13/sepomexphp/
[sensiolabs]: https://insight.sensiolabs.com/projects/0b8eb458-a6ef-4300-8950-3c4972228bbe
[coverage]: https://scrutinizer-ci.com/g/eclipxe13/sepomexphp/?branch=master
[downloads]: https://packagist.org/packages/eclipxe/sepomexphp

[badge-source]: http://img.shields.io/badge/source-eclipxe13/sepomexphp-blue.svg?style=flat-square
[badge-gitter]: https://img.shields.io/gitter/room/nwjs/nw.js.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/sepomexphp.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/eclipxe13/sepomexphp.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/sepomexphp/master.svg?style=flat-square
[badge-sensiolabs]: https://img.shields.io/sensiolabs/i/0b8eb458-a6ef-4300-8950-3c4972228bbe.svg?style=flat-square
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/eclipxe13/sepomexphp/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/sepomexphp.svg?style=flat-square
