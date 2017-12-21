<!DOCTYPE html>
<!-- (7) Formulario hijo. Unión de las funciones explodeTree y ploTree con jstree con SNMPTRANSLATE -->
<!-- Al seleccionar un elemento del árbol de MIBs, se actualiza una variable textbox name="cf1" -->
<!-- Al dar click en el botón seleccionar el valor del textbox cf1 se envía la formualrio padre -->
<!-- El comando SNMPTRANSLATE se llama en id=html -->


<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Árbol MIB</title>
	<style>
	html { margin:0; padding:0; font-size:62.5%; }
	body { max-width:800px; min-width:300px; margin:0 auto; padding:20px 10px; font-size:14px; font-size:1.4em; }
	h1 { font-size:1.8em; }
	.demo { overflow:auto; border:1px solid silver; min-height:100px; }
	</style>
	<!-- Aquí se coloca la ubicación de las librerías de jstree. Es importante verificar la ubicación de la carpeta dist -->
	<!-- y editar el src en consecuencia -->
	<link rel="stylesheet" href="./../dist/themes/default/style.min.css" />
</head>
<body>
	<h1>Árbol MIB</h1>
	<div id="html" class="demo">
	<!-- Llamada a las funciones explodeTree y Plotree-->
	<!-- Devuelve una Lista (arreglo de arreglos) en código HTML-->
	<?php
	//(4) USO DEL COMANDO Exec
	//EXEC permite ejecutar un comando de la terminal
	//Para este caso se requiere obetener el árbol MIB usando snmptranslate
	//1. argumento: se coloca el comando
	//2. argumento: Array que recibe la respuesta del comando
	//3. argumento: int que indica si se realizó exitosamente el comando

	//OJO: Seleccionar bien las opciones de snmptranslate más adecuadas
	exec ("dir /N",$arregloRespuesta, $intRespuesta);
	//El método explodeTree requiere que key sea igual al value, 
	//para ello se usa el método array_combine
	$final_array = array_combine($arregloRespuesta, $arregloRespuesta);
	
	// Ejecutar (Arreglo de arreglos), //
	$tree = explodeTree($final_array, ".", true);
	//Presentar, resultado: lista en HTML
	plotTree($tree);
	?>
	</div>
	
	<!-- Enviar el dato al formulario padre.-->
	<!-- Se activa updateParent al dar click en el botón.-->
	<h4>MIB seleccionada</h4>
	<FORM NAME="childForm" onSubmit="return updateParent();">
	<BR><INPUT TYPE="SUBMIT" VALUE="Seleccionar"><INPUT NAME="cf1" TYPE="TEXT" VALUE="">
	</FORM>
	<!-- updateParent es una función que actualiza los valores en el padre.-->
	<!-- A partir de la propiedad opener toma campos/valores del padre, mismo que se asginan a través del hijo-->
	<!-- Se cierra la ventana. -->
	<SCRIPT LANGUAGE="JavaScript">
	function updateParent() {
		opener.document.parentForm.pf1.value =document.childForm.cf1.value;
		self.close();
		return false;
	}
	</SCRIPT>
	
	
	<!-- Aquí se coloca la ubicación de las librerías de jstree. Es importante verificar la ubicación de la carpeta dist -->
	<!-- y editar el src en consecuencia -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="./../dist/jstree.min.js"></script>
	
	<script>
	// Carga datos HTML en el treeview
	$('#html').jstree();
	//Evento al escoger otro elemento del treeview
	$('#html')
		.on("changed.jstree", function (e, data) {
			if(data.selected.length) {
				var nodoSeleccionado=data.instance.get_node(data.selected[0]).text;
				// Colocar el valor del nodo seleccionado en el textbox 
				alert('Usted ha seleccionado: ' +nodoSeleccionado);
				//var x = document.getElementById("cf1");   // Obtener el elemento con el id= 
				//x.text="pepito";
				formulario=document.forms["childForm"].elements;
				formulario["cf1"].value=nodoSeleccionado;
			}
		})
		
	</script>
	
	<!-- Funciones explodeTree y plotTree en php-->
	<?php
	function explodeTree($array, $delimiter = '_', $baseval = false)
	{
		if(!is_array($array)) return false;
		$splitRE   = '/' . preg_quote($delimiter, '/') . '/';
		$returnArr = array();
		foreach ($array as $key => $val) {
			// Get parent parts and the current leaf
			$parts	= preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
			$leafPart = array_pop($parts);

			// Build parent structure
			// Might be slow for really deep and large structures
			$parentArr = &$returnArr;
			foreach ($parts as $part) {
				if (!isset($parentArr[$part])) {
					$parentArr[$part] = array();
				} elseif (!is_array($parentArr[$part])) {
					if ($baseval) {
						$parentArr[$part] = array('__base_val' => $parentArr[$part]);
					} else {
						$parentArr[$part] = array();
					}
				}
				$parentArr = &$parentArr[$part];
			}

			// Add the final part to the structure
			if (empty($parentArr[$leafPart])) {
				$parentArr[$leafPart] = $val;
			} elseif ($baseval && is_array($parentArr[$leafPart])) {
				$parentArr[$leafPart]['__base_val'] = $val;
			}
		}
		return $returnArr;
	}


	function plotTree($arr, $indent=0, $mother_run=true){
		if ($mother_run) {
			// the beginning of plotTree. We're at rootlevel
			//echo "start\n";
			echo "<ul>"; //COV: MODIFICADO, primera etiqueta
		}
		//echo "<ul>";
		foreach ($arr as $k=>$v){
			// skip the baseval thingy. Not a real node.
			if ($k == "__base_val") continue;
			else// determine the real value of this node.
			$show_val = (is_array($v) ? (isset($v["__base_val"]) ? $v["__base_val"] : "") : $v);//COV: modificado, salía error en el primer elemento
			// show the indents
			echo str_repeat("  ", $indent);
			if ($indent == 0) {
				// Nodo root. SIN PADRES
			} elseif (is_array($v)){
				// Nodo Normal. CON PADRES E HIJOS
			} else {
				// Nodo Final.  SIN HIJOS
				// Mostrar el valor
				echo "<li>";//COV: Etiqueta añadida
				echo $k . " (" . $show_val. ")" . "\n";
				echo "</li>";//COV: Etiqeuta añadida: "</li>";
			}

			if (is_array($v)) {
				// Parte del código recursiva. Muy importante.
				//
				//COV: MODIFICADO. Se coloca etiquetas en la parte recrusiva y se presenta los nodos normales
				//Para coincidir con el formato de las etiquetas en HTML
				echo "<li>";//
				echo $k . " (" . $show_val. ")" . "\n";
				echo "<ul>";
					//echo "ARRAY <br>";
					//COV: Utilizado para visualización/ comprender la función y el código original
				plotTree($v, ($indent+1), false); //Llamadaamada recursiva
				echo "</ul>";
					//echo "FIN ARRAY <br>";
					//COV: Utilizado para visualización/ comprender la función y el código original
				echo "</li>";//"</li>";
			}
		}

		if ($mother_run) {
			//echo "end\n";
			echo "</ul>"; //COV: MODIFICADO, última etiqueta
		}
	}
	?>
	
	
</body>
</html>