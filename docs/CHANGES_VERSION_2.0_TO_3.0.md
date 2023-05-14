# Main changes from version 2.0 to 3.0

Como este proyecto sigue *Semantic Versioning 2.0.0*, este archivo contiene información sobre
los cambios de compatibilidad incompatibles introducidos en la versión 3.0.

## PHP 8.1

La versión mínima requerida es PHP versión 8.1. Vea <https://www.php.net/supported-versions.php>.

## Espacio de nombre (*Namespace*)

El namespace principal del proyecto cambia de `SepomexPhp` a `Eclipxe\SepomexPhp`.

## Clases de datos implementan `JsonSerializable`

Todas las clases de datos ahora implementan la interfaz `JsonSerializable` con lo que se pueden
usar como argumento de la función `json_encode`. 

## Uso de propiedades de solo lectura

En lugar de usar *getters*, las clases de datos contienen propiedades de solo lectura.

## Método `Location::getFullName()` eliminado

El método `Location::getFullName()` fue eliminado, si bien el nombre completo se forma de la combinación
del nombre de la locación y el nombre del tipo de locación (`Los Cisnes (Fraccionamiento)`), el formato
en el que se debe presentar la información no es responsabilidad de esta clase.

## Método `DataGatewayInterface::getZipCodeData()`

El método `DataGatewayInterface::getZipCodeData()` regresa un arreglo vacío en lugar de nulo cuando no existen resultados.

## Descarga de catálogo

El origen de los datos estaba disponible desde <http://www.correosdemexico.gob.mx/datosabiertos/cp/cpdescarga.txt>,
sin embargo, este origen no se ha actualizado. SEPOMEX ahora permite la descarga a través de una aplicación web,
por lo que tuvo que implementar un *scraper* para obtener el catálogo actualizado desde
<https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx>.

Se creó el script `scripts/download.php` para descargar y extraer la base de datos de SEPOMEX.
