<?php
//La idea del proyecto total es generar un árbol MIB, lo único que se tiene para iniciar es
//el contenido del comando snmptranslate.
//Este sin embargo no nos sirve por si sólo para generar una vista de treeview que es lo que se intentará.
//Se supone que a partir del comando snmptranslate se puede generar un arreglo, esto es relativamente simple.
//Pero lo que se quiere es un "arreglo de arreglos" es decir una estructura similar a un treeview.
//Para ello se utiliza como base el código de la página http://kvz.io/blog/2007/10/03/convert-anything-to-tree-structures-in-php/
//En la primera parte se tiene un arreglo $key_files que se usa para pruebas
//Se tienen 2 funciones
//1. Explodetree que transforma un arreglo "normal"/unidimensional en un "arreglo de arreglos"/estructura de treeview
//	Esta función se deja intacta, ya que nos sirve para nuestros propósitos
//2. Plotree. Dibuja o presenta el arreglo de arreglo en una estructura de treeview (pero que no es dinámica)
//	El código original propone una presentación con "+" y "-" lo que no nos sirve dado que podemos obtener algo similar
//	con las opciones de snmptranslate
// 	Los cambios realizados están enfocados en colocar etiquetas <ul> y <li> de HTML para después poder "jugar" con el treeview en HTML


/**
 * Explode any single-dimensional array into a full blown tree structure,
 * based on the delimiters found in it's keys.
 *
 * The following code block can be utilized by PEAR's Testing_DocTest
 * <code>
 * // Input //
 * $key_files = array(
 *	 "/etc/php5" => "/etc/php5",
 *	 "/etc/php5/cli" => "/etc/php5/cli",
 *	 "/etc/php5/cli/conf.d" => "/etc/php5/cli/conf.d",
 *	 "/etc/php5/cli/php.ini" => "/etc/php5/cli/php.ini",
 *	 "/etc/php5/conf.d" => "/etc/php5/conf.d",
 *	 "/etc/php5/conf.d/mysqli.ini" => "/etc/php5/conf.d/mysqli.ini",
 *	 "/etc/php5/conf.d/curl.ini" => "/etc/php5/conf.d/curl.ini",
 *	 "/etc/php5/conf.d/snmp.ini" => "/etc/php5/conf.d/snmp.ini",
 *	 "/etc/php5/conf.d/gd.ini" => "/etc/php5/conf.d/gd.ini",
 *	 "/etc/php5/apache2" => "/etc/php5/apache2",
 *	 "/etc/php5/apache2/conf.d" => "/etc/php5/apache2/conf.d",
 *	 "/etc/php5/apache2/php.ini" => "/etc/php5/apache2/php.ini"
 * );
 *
 * // Execute //
 * $tree = explodeTree($key_files, "/", true);
 *
 * // Show //
 * print_r($tree);
 *
 * // expects:
 * // Array
 * // (
 * //	 [etc] => Array
 * //		 (
 * //			 [php5] => Array
 * //				 (
 * //					 [__base_val] => /etc/php5
 * //					 [cli] => Array
 * //						 (
 * //							 [__base_val] => /etc/php5/cli
 * //							 [conf.d] => /etc/php5/cli/conf.d
 * //							 [php.ini] => /etc/php5/cli/php.ini
 * //						 )
 * //
 * //					 [conf.d] => Array
 * //						 (
 * //							 [__base_val] => /etc/php5/conf.d
 * //							 [mysqli.ini] => /etc/php5/conf.d/mysqli.ini
 * //							 [curl.ini] => /etc/php5/conf.d/curl.ini
 * //							 [snmp.ini] => /etc/php5/conf.d/snmp.ini
 * //							 [gd.ini] => /etc/php5/conf.d/gd.ini
 * //						 )
 * //
 * //					 [apache2] => Array
 * //						 (
 * //							 [__base_val] => /etc/php5/apache2
 * //							 [conf.d] => /etc/php5/apache2/conf.d
 * //							 [php.ini] => /etc/php5/apache2/php.ini
 * //						 )
 * //
 * //				 )
 * //
 * //		 )
 * //
 * // )
 * </code>
 *
 * @author	Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author	Lachlan Donald
 * @author	Takkie
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: explodeTree.inc.php 89 2008-09-05 20:52:48Z kevin $
 * @link	  http://kevin.vanzonneveld.net/
 *
 * @param array   $array
 * @param string  $delimiter
 * @param boolean $baseval
 *
 * @return array
 */
 
// Input //
$key_files = array(
"/etc/php5" => "/etc/php5",
"/etc/php5/cli" => "/etc/php5/cli",
"/etc/php5/cli/conf.d" => "/etc/php5/cli/conf.d",
"/etc/php5/cli/php.ini" => "/etc/php5/cli/php.ini",
"/etc/php5/conf.d" => "/etc/php5/conf.d",
"/etc/php5/conf.d/mysqli.ini" => "/etc/php5/conf.d/mysqli.ini",
"/etc/php5/conf.d/curl.ini" => "/etc/php5/conf.d/curl.ini",
"/etc/php5/conf.d/snmp.ini" => "/etc/php5/conf.d/snmp.ini",
"/etc/php5/conf.d/gd.ini" => "/etc/php5/conf.d/gd.ini",
"/etc/php5/conf.d/pepito" => "/etc/php5/conf.d/pepito",
"/etc/php5/conf.d/pepito/1" => "/etc/php5/conf.d/pepito/1",
"/etc/php5/conf.d/pepito/2" => "/etc/php5/conf.d/pepito/2",
"/etc/php5/apache2" => "/etc/php5/apache2",
"/etc/php5/apache2/conf.d" => "/etc/php5/apache2/conf.d",
"/etc/php5/apache2/php.ini" => "/etc/php5/apache2/php.ini"
);

// Execute //
$tree = explodeTree($key_files, "/", true);
// Show //
//print_r($tree);
plotTree($tree);

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