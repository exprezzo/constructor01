<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/busqueda.js"></script>
<?php 		
	$id=$_PETICION->controlador.'-'.$_PETICION->accion;
	$_REQUEST['tabId'] =$id;	
	
?>
<script>			
	$( function(){		
		var config={
			tab:{
				id:'<?php echo $_REQUEST['tabId']; ?>'
			},
			controlador:{
				nombre:'<?php echo $_PETICION->controlador; ?>'
			},
			modulo:{
				nombre:'<?php echo $_PETICION->modulo; ?>'
			},
			catalogo:{
				nombre:''

			},			
			pk:"id"
			
		};				
		 var lista=new BusquedaControlador();
		 lista.init(config);		
	});
</script>

<div class="contenedor_catalogo" id="<?php echo $id; ?>">	
	<div id="titulo">
    	<h1>{TITULO}</h1>
	</div>		
	<div id="cuerpo" >				
		<div id="contenedorDatos2">
		<table class="grid_busqueda">
			<thead>				
			</thead>  	 
			<tbody>			
			</tbody>
		</table>
		<div id="contenedorMenu2" class="toolbarEdicion">
				<input type="submit" value="Nuevo" class="botonNuevo btnNuevo">
				<input type="submit" value="Editar" class="botonNuevo btnEditar">
				<input type="submit" value="Eliminar" class="botonNuevo sinMargeDerecho btnEliminar">
			</div>
	</div>
	</div>
	
</div>
