<?php
	$id=$_PETICION->controlador.'-'.$_PETICION->accion;
	$_REQUEST['tabId'] =$id;
	
	
?>
<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/edicion.js"></script>

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
		 var editor=new EdicionConexiones();
		 editor.init(config);	
		//-----
		
	});
</script>
<style>

</style>
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Nueva Conexion</h1>
	</div>
	<div id="cuerpo">
		<div id="contenedorDatos2">
			<form class="frmEdicion" style="">
				
				<div class="inputBox contenedor_id oculto" style=""  >
					<label style="">Id:</label>
					<input title="Id" type="text" name="id" class="entradaDatos" value="<?php echo $this->datos['id']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_host" style=""  >
					<label style="">Host:</label>
					<input title="Host" type="text" name="host" class="entradaDatos" value="<?php echo $this->datos['host']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_db_name" style=""  >
					<label style="">DB Name:</label>
					<input title="Nombre de la base de datos" type="text" name="db_name" class="entradaDatos" value="<?php echo $this->datos['db_name']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_user" style=""  >
					<label style="">User:</label>
					<input title="User" type="text" name="user" class="entradaDatos" value="<?php echo $this->datos['user']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_pass" style=""  >
					<label style="">Pass:</label>
					<input title="Pass" type="text" name="pass" class="entradaDatos" value="<?php echo $this->datos['pass']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_fk_app oculto" style=""  >
					<label style="">App:</label>
					<input title="" type="text" name="fk_app" class="entradaDatos" value="<?php echo $this->datos['fk_app']; ?>" style="width:500px;" />
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