<?php
	$id=$_PETICION->controlador.'-'.$_PETICION->accion;
	$_REQUEST['tabId'] =$id;
	
	
?>
<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/edicion.js"></script>

<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/paginas_de_autor.js"></script>
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
		 var editor=new EdicionAutores();
		 editor.init(config);	
		//-----
		
		var tabId='#'+config.tab.id;
		config={
			padre:editor,
			tabId:'#<?php echo $_REQUEST['tabId']; ?>',
			elementos: <?php echo json_encode($this->datos['paginasDeAutor']); ?>,
			target:'.tabla_paginas',
			contenedor:'.contenedor_tabla_paginas',
		};

		var paginasDeAutor = new PaginasDeAutor();		
		paginasDeAutor.init(config);
				
	});
</script>
<style>

</style>
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Nuevo Autor</h1>
	</div>
	<div id="cuerpo">
		<div id="contenedorDatos2">
			<form class="frmEdicion" style="">
				
				<div class="inputBox contenedor_id" style=""  >
					<label style="">Id:</label>
					<input type="text" name="id" class="entradaDatos" value="<?php echo $this->datos['id']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_nick" style=""  >
					<label style="">Nick:</label>
					<input type="text" name="nick" class="entradaDatos" value="<?php echo $this->datos['nick']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_pass" style=""  >
					<label style="">Pass:</label>
					<input type="text" name="pass" class="entradaDatos" value="<?php echo $this->datos['pass']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_email" style=""  >
					<label style="">Email:</label>
					<input type="text" name="email" class="entradaDatos" value="<?php echo $this->datos['email']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_rol" style=""  >
					<label style="">Rol:</label>
					<input type="text" name="rol" class="entradaDatos" value="<?php echo $this->datos['rol']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_fbid" style=""  >
					<label style="">Fbid:</label>
					<input type="text" name="fbid" class="entradaDatos" value="<?php echo $this->datos['fbid']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_name" style=""  >
					<label style="">Name:</label>
					<input type="text" name="name" class="entradaDatos" value="<?php echo $this->datos['name']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_picture" style=""  >
					<label style="">Picture:</label>
					<input type="text" name="picture" class="entradaDatos" value="<?php echo $this->datos['picture']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_originalName" style=""  >
					<label style="">OriginalName:</label>
					<input type="text" name="originalName" class="entradaDatos" value="<?php echo $this->datos['originalName']; ?>" style="width:500px;" />
				</div>
				<div class="tabla contenedor_tabla_paginas" style=""  >
					<div class="toolbar_detalles" style="margin-right: 44px;">
						<input type="button" value="" class="btnAgregar" id="botonAgregar"/>
						<input type="button" value="" class="btnEliminar" id="botonEliminar" />
					</div>
					<h1 style="">Paginas</h1>
					<table class="tabla_paginas">
						<thead></thead>
						<tbody></tbody>
					</table>
					<div id="<?php echo $id; ?>-dialog-confirm-eliminar-pagina" title="&iquest;Eliminar Pagina?">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>&iquest;Eliminar Pagina?</p>
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