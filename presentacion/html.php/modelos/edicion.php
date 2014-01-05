
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
				nombre:'Modelo C',
				modelo:'Modeloc'
			},			
			pk:"id"
			
		};				
		 var editor=new Edicionmodelos();
		 editor.init(config);		
	});
</script>
<style>
.entradaDatos, input[role="textbox"]{
	/* float:right; */
	background-color:#f2f2f2  !important;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	border-top: 0px;
	border-right: 0px;
	border-left: 0px;
	border-bottom-color:#508b96  !important;
	border-bottom-style:solid !important;
	border-bottom-width:1px  !important;
	-webkit-box-shadow: 0px 3px #d6e6e9  !important;
    -moz-box-shadow: 0px 3px #d6e6e9  !important;
	box-shadow: 0px 3px #d6e6e9  !important;
	/* width:365px; */
	height:23px;
	/*height:43px;*/
	/* margin-bottom:25px; */
	font-family:"OpenSans-Light", sans-serif;
	font-size:17px;
	
	color:black  !important;
}
</style>
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Nuevo Modelo</h1>
	</div>
	<div id="cuerpo" >				
		<div id="contenedorDatos2">
			<form class="frmEdicion" style="">
				
				<div class="inputBox" style=""  >
					<label style="">Id:</label>
					<input type="text" name="id" class="entradaDatos" value="<?php echo $this->datos['id']; ?>" style="width:500px;" />
				</div>

				<div class="inputBox" style=""  >
					<label style="">Nombre:</label>
					<input type="text" name="nombre" class="entradaDatos" value="<?php echo $this->datos['nombre']; ?>" style="width:500px;" />
				</div>

				<div class="inputBox" style=""  >
					<label style="">Tabla:</label>
					<input type="text" name="tabla" class="entradaDatos" value="<?php echo $this->datos['tabla']; ?>" style="width:500px;" />
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