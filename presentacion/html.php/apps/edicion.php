<?php
	$id=$_PETICION->controlador.'-'.$_PETICION->accion;
	$_REQUEST['tabId'] =$id;
	
	
?>
<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/edicion.js"></script>

<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/conexiones_de_app.js"></script>
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
		 var editor=new EdicionApps();
		 editor.init(config);	
		//-----
		
		var tabId='#'+config.tab.id;
		configDet={
			padre:editor,
			tabId:'#<?php echo $_REQUEST['tabId']; ?>',
			elementos: <?php echo json_encode($this->datos['conexionesDeApp']); ?>,
			target:'.tabla_conexiones',
			contenedor:'.contenedor_tabla_conexiones',
		};

		var conexionesDeApp = new ConexionesDeApp();		
		conexionesDeApp.init(configDet);
				
	});
</script>
<style>

</style>
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Nueva App</h1>
	</div>
	<div id="cuerpo">
		<div id="contenedorDatos2">
			<form class="frmEdicion" style="">
				
				<div class="inputBox contenedor_id oculto" style=""  >
					<label style="">Id:</label>
					<input title="Id" type="text" name="id" class="entradaDatos" value="<?php echo $this->datos['id']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_nombre" style=""  >
					<label style="">Nombre:</label>
					<input title="Nombre" type="text" name="nombre" class="entradaDatos" value="<?php echo $this->datos['nombre']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_ubicacion" style=""  >
					<label style="">Ubicacion:</label>
					<input title="Ubicacion" type="text" name="ubicacion" class="entradaDatos" value="<?php echo $this->datos['ubicacion']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_logo" style=""  >
					<label style="">Logo:</label>
					<input title="Logo" type="text" name="logo" class="entradaDatos" value="<?php echo $this->datos['logo']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_favicon" style=""  >
					<label style="">Favicon:</label>
					<input title="Favicon" type="text" name="favicon" class="entradaDatos" value="<?php echo $this->datos['favicon']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_descripcion" style=""  >
					<label style="">Descripcion:</label>
					<input title="Descripcion" type="text" name="descripcion" class="entradaDatos" value="<?php echo $this->datos['descripcion']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_tags" style=""  >
					<label style="">Tags:</label>
					<input title="Tags" type="text" name="tags" class="entradaDatos" value="<?php echo $this->datos['tags']; ?>" style="width:500px;" />
				</div>
				<div class="tabla contenedor_tabla_conexiones" style="position: relative; margin-top: 26px;"  >
					
					<h1 style="display: inline-block; margin-bottom: 6px;">Conexiones</h1>
					<div class="toolbar_detalles" style="position: absolute; right: 0; top: -2PX;">
						<input type="button" value="" class="btnAgregar" id="botonAgregar"/>
						<input type="button" value="" class="btnEliminar" id="botonEliminar" />
					</div>
					<table class="tabla_conexiones">
						<thead></thead>
						<tbody></tbody>
					</table>
					<div id="<?php echo $id; ?>-dialog-confirm-eliminar-conexion" title="&iquest;Eliminar Conexion?">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>&iquest;Eliminar Conexion?</p>
					</div> 
				</div>
			</form>
			<div id="contenedorMenu2" class="toolbarEdicion">
				<input type="submit" value="Nuevo" class="botonNuevo btnNuevo">
				<input type="submit" value="Guardar" class="botonNuevo btnGuardar">
				<input type="submit" value="PDF" class="botonNuevo btnPdf">
				<input type="submit" value="Eliminar" class="botonNuevo sinMargeDerecho btnDelete">
			</div>
		</div>
	</div>
</div>