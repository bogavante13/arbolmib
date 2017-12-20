<!DOCTYPE html>
<!-- (2) Para hacer un treeview dinámico se utiliza jstree.  -->
<!-- Este archivo es una modificación del demo que viene con jstree.   -->
<!-- Existen varias opciones para cargar datos en el el treeview. Se utiliza únicamente con el código HTML  -->
<!-- Los datos que se ingresan al treeview se encuentran con el id=html -->

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>jstree basic demos</title>
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
	<h1>HTML demo</h1>
	<div id="html" class="demo">
	<!-- Lista (arreglo de arreglos) en código HTML para propósitos de PRUEBA-->
	<!-- Se espera generar este código HTML con las funciones explodeTree y Plotree-->
	<!-- La "indentación" solo tiene propósitos de visualización-->
			<ul>
				<li>etc ()
					<ul>  
						<li>php5 (/etc/php5)
							<ul>    
								<li>cli (/etc/php5/cli)
									<ul>      
										<li>conf.d (/etc/php5/cli/conf.d)
										</li>      
										<li>php.ini (/etc/php5/cli/php.ini)
										</li>
									</ul>
								</li>    
								<li>conf.d (/etc/php5/conf.d)
									<ul>      
										<li>mysqli.ini (/etc/php5/conf.d/mysqli.ini)
										</li>      
										<li>curl.ini (/etc/php5/conf.d/curl.ini)
										</li>      
										<li>snmp.ini (/etc/php5/conf.d/snmp.ini)
										</li>      
										<li>gd.ini (/etc/php5/conf.d/gd.ini)
										</li>      
										<li>pepito (/etc/php5/conf.d/pepito)
											<ul>
												<li>1 (/etc/php5/conf.d/pepito/1)
												</li>
												<li>2 (/etc/php5/conf.d/pepito/2)
												</li>
											</ul>
										</li>
									</ul>
								</li>    
								<li>apache2 (/etc/php5/apache2)
									<ul>      
										<li>conf.d (/etc/php5/apache2/conf.d)
										</li>      
										<li>php.ini (/etc/php5/apache2/php.ini)
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
	</div>

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
				alert('The selected node is: ' + data.instance.get_node(data.selected[0]).text);
			}
		})
	</script>
		
</body>
</html>