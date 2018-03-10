# Origen de los datos

**This document is in spanish**

SEPOMEX: https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales/resource/13887b9a-f770-4276-8b80-f092cd886b44

A pesar de la leyenda restrictiva en el archivo descargado, la información es liberada bajo licencia LIBRE USO MX por lo
que mientras se cite la fuente y se utilice en los términos de la misma licencia se permite su distribución y uso de la información
incluso para fines comerciales.

# Estructura origen:

```
d_codigo            Descripción del código
d_asenta            Asentamiento
d_tipo_asenta       Nombre del asentamiento
D_mnpio             Nombre del municipio
d_estado            Nombre del estado
d_ciudad            Nombre de la ciudad
d_CP                Nombre de la oficina administradora
c_estado            clave estado
c_oficina           Clave de la oficina administradora
c_CP                (vacío, no usado)
c_tipo_asenta       clave del tipo de asentamiento
c_mnpio             clave municipio del estado
id_asenta_cpcons    clave consecutiva del asentamiento
d_zona              Nombre de la zona
c_cve_ciudad        clave ciudad
```

# Información que se puede extraer

Primero, no podemos dar por hecho que la información no va a cambiar,
podría cambiar, por ilógico que parezca se pueden crear estados, anexarse a ciudades,
cambiar el nombre de una ciudad, etc... por lo que debe ser creada de esta forma desde su origen.

### Listado de estados:

Lista de las 32 entidades federativas, la información corresponde a INEGI, por lo que el estado
en lugar de llamarse "Veracruz" se llama "Veracruz de Ignacio de la Llave",
por eso crearemos la tabla con un campo de nombre común de los ya conocidos.

### Listado de delegaciones/municipios

Cada municipio tiene una clave numérica que está relacionada directamente con el estado,
por lo que en realidad es una llave compuesta.

### Listado de ciudades

La clave se comporta como el municipio (estado + ciudad),
la diferencia es que un código postal puede estar o no relacionada con una ciudad.

No todos los códigos postales de un municipio pertenecen a una ciudad por lo que no todos
los municipios tienen una y sola una ciudad.

También existen códigos postales que están relacionados con una ciudad y al mismo tiempo
no están relacionados con alguna, lo único que se me ocurre es que el código postal es tan
amplio que abarca zonas que pertenecen a una ciudad y zonas que están fuera de la ciudad.

La entidad que sí tiene una relación directa con la ciudad es el asentamiento (colonia) por lo que
podemos decir que un asentamiento pertenece en su totalidad o no a una ciudad.

### Zonas

Hay tres zonas: Rural, semiurbano y urbano y no viene especificada su clave.
No encuentro en qué sentido esta información podría ser útil.

### Asentamiento

Se puede entender asentamiento como "Colonia",
en el catálogo podremos encontrar colonias en el mismo municipio y con el mismo nombre,
pero con diferente clave. Esto es porque el tipo de asentamiento cambia.

Se puede identificar un asentamiento de forma única por el conjunto de estos campos:
nombre + tipo de asentamiento + municipio + estado.

Sin embargo, para cada uno de estos registros le corresponden varios códigos postales.

### Tipos de asentamiento

Los tipos de asentamiento juegan el papel de un prefijo para la colonia (asentamiento),
por ejemplo: en la delegación Álvaro Obregón de la Ciudad de México
existen dos asentamientos llamados "Molino de Santo Domingo", solo que uno es "Unidad habitacional" y el otro es "Colonia".

Como esta base de datos es por SEPOMEX y no por INEGI,
cuenta con un tipo de asentamiento llamado "Gran usuario",
no es información geográfica y representa únicamente a los intereses de SEPOMEX,
pero vaya, es un código postal.

Por lo anterior, un asentamiento debería llamarse asentamiento + tipo de asentamiento, por ejemplo:

* Molino de Santo Domingo (Unidad habitacional)
* Molino de Santo Domingo (Colonia)

### Información inútil

* Los campos `d_CP` y `C_oficina` son idénticos,
  se refiere a la oficina de SEPOMEX que les reparte a un código postal,
  no hay una descripción de su ubicación o su nombre así que podemos considerar no nos sirve.
* El campo `c_CP` es un campo vacío
* El campo `id_asenta_cpcons` es un consecutivo de códigos postales,
  algo así como una llave autonumérica, encontraremos varios registros
  en donde lo único que cambia es esta clave y los otros campos: asentamiento, tipo de asentamiento,
  municipio y estado son los mismos.

### La tabla de códigos postales

La información la tabla de códigos postales debería contener solamente:
código postal + municipio + estado

El código postal en México es un número de 5 dígitos con ceros a la izquierda,
eso lo convierte en un texto, pero solo para su visualización, para la base de datos será considerado un número
y de esa forma agilizar las consultas.

### Información valiosa

Gracias a esta base de datos podríamos saber, por ejemplo:

* Los estados de México
* Para un estado, las ciudades que lo conforman
* Para un estado, los municipios que lo conforman
* Para una ciudad, qué municipios están incluídos
* Las colonias (asentamientos) que conforman un municipio

## Estructura

Lo que se pretende es normalizar la información por lo que la estructura debería ser:

```
Nomenclatura: entidad <- entidad_padre

estado
tipo asentamiento
municipio               <- estado
ciudad                  <- estado
asentamiento            <- tipo asentamiento
                        <- ciudad (puede ser nulo)
codigo                  <- municipio
codigos x asentamiento  <- asentamiento
                        <- codigo
```

## Cómo actualizar la base de datos

Existe un script `create-sqlite-from-raw.php` que toma el archivo de texto de sepomex
y a partir de él crea una base de datos de sqlite.

1. Crear las estructuras
1. Leer cada una de las líneas de texto e insertarlas en la tabla raw
1. Llenar por consultas de SQL las tablas


### Consultas para crear la estructura

```sql
CREATE TABLE raw (d_codigo text, d_asenta text, d_tipo_asenta text, d_mnpio text, d_estado text, d_ciudad text, d_cp text, c_estado text, c_oficina text, c_cp text, c_tipo_asenta text, c_mnpio text, id_asenta_cpcons text, d_zona text, c_cve_ciudad text);
CREATE TABLE states (id integer primary key not null, name text not null);
CREATE TABLE districts (id integer primary key autoincrement not null, idstate integer not null, name text not null, idraw text);
CREATE TABLE cities (id integer primary key autoincrement not null, idstate integer not null, name text not null, idraw text);
CREATE TABLE locationtypes (id integer primary key not null, name text not null);
CREATE TABLE locations (id integer primary key autoincrement not null, idlocationtype integer not null, iddistrict integer not null, idcity integer default null, name text not null);
CREATE TABLE zipcodes (id integer primary key not null, iddistrict int not null);
CREATE TABLE locationzipcodes (idlocation integer not null, zipcode integer not null, primary key(idlocation, zipcode));
```


### Consultas para llenar los datos
```sql

-- states (estados)
INSERT INTO states
    SELECT DISTINCT CAST(c_estado AS INTEGER) as id, d_estado as name FROM raw ORDER BY c_estado;
-- districts (municipios) 
INSERT INTO districts
    SELECT DISTINCT null as id, CAST(c_estado AS INTEGER) as idstate, d_mnpio as name, CAST(c_mnpio AS INTEGER) as idraw FROM raw ORDER BY c_estado, c_mnpio;
-- cities (ciudades)
INSERT INTO cities
    SELECT DISTINCT null as id, CAST(c_estado AS INTEGER) as idstate, d_ciudad as name, CAST(c_cve_ciudad AS INTEGER) as idraw FROM raw WHERE (d_ciudad <> "") ORDER BY c_estado, c_cve_ciudad;
-- locationtypes (tipo de asentamiento)
INSERT INTO locationtypes
    SELECT DISTINCT CAST(c_tipo_asenta AS INTEGER) AS id, d_tipo_asenta AS name FROM raw ORDER BY c_tipo_asenta;
-- locations (asentamientos)
INSERT INTO locations
    SELECT DISTINCT NULL AS id, t.id as idlocationtype, d.id AS iddistrict, c.id AS idcity, d_asenta AS name
    FROM raw AS r
    INNER JOIN locationtypes as t ON (t.name = r.d_tipo_asenta)
    INNER JOIN districts as d ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))
    LEFT JOIN cities as c ON (c.idraw = CAST(c_cve_ciudad AS INTEGER) AND c.idstate = CAST(c_estado AS INTEGER));
-- zipcodes (codigos postales)
INSERT INTO zipcodes
    SELECT DISTINCT CAST(d_codigo AS INTEGER) AS id, d.id AS iddistrict
    FROM raw AS r
    INNER JOIN districts AS d ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER));
-- locationzipcodes (relación N a N de asentamientos y códigos postales)
INSERT INTO locationzipcodes
    SELECT DISTINCT l.id AS idlocation, CAST(d_codigo AS INTEGER) AS zipcode
    FROM raw AS r
    INNER JOIN locationtypes AS t ON (t.name = r.d_tipo_asenta)
    INNER JOIN districts AS d ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))
    INNER JOIN locations AS l ON (t.id = l.idlocationtype AND d.id = l.iddistrict AND l.name = r.d_asenta)

```
