# Comodo
Libreria de PHP para funciones CRUD y demas.
## Inicio
Primero se debe llamar el archivo comodo.php 
```php
<?php
include "comodo.php";
//$obj = new Comodo('hostname','usuario','password','database','puerto_opcional','socket_opcional');
$obj = new Comodo('localhost','root','password','test');
```
Con esto ya tendremos la libreria inicializada y lista para usarse a traves del objeto `$obj`
## Guardar un registro
A traves de la funcion create se pueden guardar regisstro de la siguiente manera

```php
$table="Nombre_de_la_tabla";
$data = array(
    "Nombre_campo1"=>$variable_con_valor1,
    "Nombre_campo2"=>$variable_con_valor2
  );
echo $obj->create($table, $data);
//Esto retornara un Ok o un Error
```
## Eliminar un registro
A traves de la funcion delete se pueden eliminar registro de la siguiente manera

```php
$table="Nombre_de_la_tabla";
$where = "id=1";
echo $obj->delete($table, $where);
//Esto retornara un Ok o un Error
```

## Actualizar un registro
A traves de la funcion update se pueden actualizar registros de la siguiente manera

```php
$table="Nombre_de_la_tabla";
$data = array(
    "Nombre_campo1"=>$variable_con_valor1,
    "Nombre_campo2"=>$variable_con_valor2
  );
$where = "id=1";
echo $obj->update($table,$data,$where);
//Esto retornara un Ok o un Error
```

