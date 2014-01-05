<?php
	$id=$_PETICION->controlador.'-'.$_PETICION->accion;
	$_REQUEST['tabId'] =$id;
	
	
?>
<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/edicion.js"></script>

<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/conceptos_de_cotizacion.js"></script>
<script>			
	$( function(){	
		
		//---------------------
		<?php
		$resAnt = empty($_SESSION['res']) ? array() : $_SESSION['res'];
		unset($_SESSION['res']);
		?>
		var resAnt = <?php echo json_encode($resAnt); ?>;
		
		if (resAnt.success != undefined ){			
			var title='', msg	=resAnt.msg, icon='';
			if(resAnt.success){
				icon=kore.url_web+'imagenes/yes.png';
				title= 'Success';					
			}else{
				icon= kore.url_web+'imagenes/error.png';
				title= 'Error';
			}
			
			$.gritter.add({
				position: 'bottom-left',
				title:title,
				text: msg,
				image: icon,
				class_name: 'my-sticky-class'
			});
		}
		//---------------------
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
				nombre:'Paginas',
				modelo:'Pagina'
			},			
			pk:"id"
			
		};				
		 var editor=new EdicionCotizaciones();
		 editor.init(config);	
		//-----
		
		var tabId='#'+config.tab.id;
		config={
			padre:editor,
			tabId:'#<?php echo $_REQUEST['tabId']; ?>',
			elementos: <?php echo json_encode($this->datos['conceptosDeCotizacion']); ?>,
			target:'.tabla_conceptos',
			contenedor:'.contenedor_tabla_conceptos',
		};

		var conceptosDeCotizacion = new ConceptosDeCotizacion();		
		conceptosDeCotizacion.init(config);
				
	});
</script>
<style>

</style>
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Nueva Cotizacion</h1>
	</div>
	<div id="cuerpo">
		<div id="contenedorDatos2">
			<form class="frmEdicion" style="">
				
				<div class="inputBox contenedor_id" style=""  >
					<label style="">Id:</label>
					<input type="text" name="id" class="entradaDatos" value="<?php echo $this->datos['id']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_serie" style=""  >
					<label style="">Serie:</label>
					<input type="text" name="serie" class="entradaDatos" value="<?php echo $this->datos['serie']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_folio" style=""  >
					<label style="">Folio:</label>
					<input type="text" name="folio" class="entradaDatos" value="<?php echo $this->datos['folio']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_notas" style=""  >
					<label style="">Notas:</label>
					<input type="text" name="notas" class="entradaDatos" value="<?php echo $this->datos['notas']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_fecha" style=""  >
					<label style="">Fecha:</label>
					<input type="text" name="fecha" class="entradaDatos" value="<?php echo $this->datos['fecha']; ?>" style="width:500px;" />
				</div>
				<div class="tabla contenedor_tabla_conceptos" style=""  >
					<div class="toolbar_detalles" style="margin-right: 44px;">
						<input type="button" value="" class="btnAgregar" id="botonAgregar"/>
						<input type="button" value="" class="btnEliminar" id="botonEliminar" />
					</div>
					<h1 style="">Conceptos</h1>
					<table class="tabla_conceptos">
						<thead></thead>
						<tbody></tbody>
					</table>
					<div id="<?php echo $id; ?>-dialog-confirm-eliminar-concepto_cotizacion" title="&iquest;Eliminar Concepto_cotizacion?">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>&iquest;Eliminar Concepto_cotizacion?</p>
					</div> 
				</div>
			</form>
			<div id="contenedorMenu2" class="toolbarEdicion">
				<input type="submit" value="Nuevo" class="botonNuevo btnNuevo">
				<input type="submit" value="Guardar" class="botonNuevo btnGuardar">
				<input type="submit" value="Eliminar" class="botonNuevo sinMargeDerecho btnDelete">
			</div>
		</div>
	</div>
</div>