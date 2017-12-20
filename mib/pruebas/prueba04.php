
<?php
//(4) USO DEL COMANDO Exec
//EXEC permite ejecutar un comando de la terminal
//Para este caso se requiere obetener el árbol MIB usando snmptranslate
//1. argumento: se coloca el comando
//2. argumento: Array que recibe la respuesta del comando
//3. argumento: int que indica si se realizó exitosamente el comando

//OJO: Seleccionar bien las opciones de snmptranslate más adecuadas
exec ("snmptranslate -Tl",$arregloRespuesta, $intRespuesta);
echo $intRespuesta;
print_r($arregloRespuesta);
//El método explodeTree requiere que key sea igual al value, 
//para ello se usa el método array_combine
$final_array = array_combine($arregloRespuesta, $arregloRespuesta);

print_r($final_array);
?>
