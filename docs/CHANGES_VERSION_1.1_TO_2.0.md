# Main changes from version 1.1 to 2.0

As this project is following Semantic Versioning 2.0.0 this file is contains information about the
breaking compatibility changes introduced in 2.0


## PHP 7.0

The new version of this library is compatible with PHP 7.0. It uses strict types and
[type declarations](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration).


## Value objects

In version 1.1 properties of objects where public, now they are value objects,
this means that you cannot access as properties, and also you cannot change its values.


## Import process

The import process can be made in only one step using `SepomexPhp\Importer\PdoImporter::import`


## Rename states

Some states have their legal names like 'Veracruz de Ignacio de la Llave',
you can rename any state name by passing an array to the import process
or calling `SepomexPhp\Importer\PdoImporter::renameStates`.

The static method `SepomexPhp\Importer\PdoImporter::commonSatesRename` contains a list of common replaces.

## Local database

The local database of zipcodes is stored inside `assets` folder.
If you get this project and run `scripts/create-sqlite-from-raw.php` it will create two files:

- `sepomex.db` contains the sqlite database
- `sepomex.txt` contains the source raw file 
