# SepomexPhp - Servicio postal Mexicano PHP Library

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

Do you have your own dataset of Sepomex? You can extend this library, just create `DataGateway` that
implements the methods and get the data from anywhere.


# About the SEPOMEX information

Sepomex distribute its database of postal codes under a very restrictive clause:

>> *El Catálogo Nacional de Códigos Postales, es elaborado por Correos de México y se proporciona en forma gratuita para uso particular,
no estando permitida su comercialización, total o parcial, ni su distribución a terceros bajo ningún concepto.*

That means:

* The data is ditributed only for personal use
* Cannot redistribute the information (total or partial) to anyone (insert a big WTF! here)
* Cannot create any profit of the information

So, I'm not allowed to share with you the SEPOMEX public information (in the original form or changed).
You can download it from: http://www.sepomex.gob.mx/ServiciosLinea/Paginas/DescargaCP.aspx

If you run the script `scripts/create-sqlite-from-raw.php` you will create a sqlite database
with the same information but normalized.

# What is working

Right now you can only search a mexican zip code and it will give you the information about:

* The state *Estado* where it is located, like 'Tabasco'
* The district *Delegación/Municipio* where it is located, like 'Centro'
* A list of locations *Colonias*, each location contains:
    * Name of the location, like '1 de mayo'
    * Type of the location, like 'Colonia' or 'Unidad habitacional'
    * City Name, like 'Villahermosa'

The city is located under the location entity because the same postal code can include some place inside the city
and some places outside the city. Yes, this is how it works in Mexico.

# What is planned

Search from global to specific:

- [ ] Get the full list of states
- [ ] Select a state and get all cities and districts.
- [ ] Select a district and get all locations
- [ ] Select a city and get all locations
- [ ] Select a location and get all zip codes

Other things to do:

- [ ] Create an API for public access.
- [ ] Create a sepomex.txt with fake information that can be distributed with the project, useful for tests

# Are you interested on help to this project?

You are welcome to join! Please try to follow up PSR-1, PSR-2 and PSR-4 conventions.

Lets make this agnostic, a simple library, without depends on any framework, except for an implementation (like an API)

# Licence

The MIT License (MIT). Please see [License File](LICENSE) for more information.





