<?php
	$id=$_PETICION->controlador.'-'.$_PETICION->accion;
	$_REQUEST['tabId'] =$id;
	
	
if ( !empty( $this->datos['fk_concepto'] ) ){
			$concepto_listado=array();
			$concepto_listado[]=array('id'=>$this->datos['fk_concepto'],'nombre'=>$this->datos['nombre_concepto'] );
			$this->concepto_listado = $concepto_listado;
		}else{
			$mod=new conceptoModelo();
			$objs=$mod->buscar( array() );		
			$this->concepto_listado = $objs['datos'];
		}
if ( !empty( $this->datos['fk_um'] ) ){
			$unidad_listado=array();
			$unidad_listado[]=array('id'=>$this->datos['fk_um'],'nombre'=>$this->datos['nombre_unidad'] );
			$this->unidad_listado = $unidad_listado;
		}else{
			$mod=new unidadModelo();
			$objs=$mod->buscar( array() );		
			$this->unidad_listado = $objs['datos'];
		}
if ( !empty( $this->datos['fk_cotizacion'] ) ){
			$cotizacion_listado=array();
			$cotizacion_listado[]=array('id'=>$this->datos['fk_cotizacion'],'folio'=>$this->datos['folio_cotizacion'] );
			$this->cotizacion_listado = $cotizacion_listado;
		}else{
			$mod=new cotizacionModelo();
			$objs=$mod->buscar( array() );		
			$this->cotizacion_listado = $objs['datos'];
		}
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
		 var editor=new EdicionConceptos_cotizacion();
		 editor.init(config);		
	});
</script>
<style>

</style>
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Nuevo Concepto de Cotizacion</h1>
	</div>
	<div id="cuerpo">
		<div id="contenedorDatos2">
			<form class="frmEdicion" style="">
				
				<div class="inputBox contenedor_id" style=""  >
					<label style="">Id:</label>
					<input type="text" name="id" class="entradaDatos" value="<?php echo $this->datos['id']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_fk_concepto" style=""  >
					<label style="">Fk_concepto:</label>
					<select name="fk_concepto" class="entradaDatos" style="width:250px;">
						<?php
							foreach($this->concepto_listado as $concepto){
								echo '<option value="'.$concepto['id'].' " >'.$concepto['nombre'].'</option>';
							}
						?>
					</select>
				</div>
				<div class="inputBox contenedor_cantidad" style=""  >
					<label style="">Cantidad:</label>
					<input type="text" name="cantidad" class="entradaDatos" value="<?php echo $this->datos['cantidad']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_fk_um" style=""  >
					<label style="">Fk_um:</label>
					<select name="fk_um" class="entradaDatos" style="width:250px;">
						<?php
							foreach($this->unidad_listado as $unidad){
								echo '<option value="'.$unidad['id'].' " >'.$unidad['nombre'].'</option>';
							}
						?>
					</select>
				</div>
				<div class="inputBox contenedor_precio_unitario" style=""  >
					<label style="">Precio_unitario:</label>
					<input type="text" name="precio_unitario" class="entradaDatos" value="<?php echo $this->datos['precio_unitario']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_importe" style=""  >
					<label style="">Importe:</label>
					<input type="text" name="importe" class="entradaDatos" value="<?php echo $this->datos['importe']; ?>" style="width:500px;" />
				</div>
				<div class="inputBox contenedor_fk_cotizacion" style=""  >
					<label style="">Fk_cotizacion:</label>
					<select name="fk_cotizacion" class="entradaDatos" style="width:250px;">
						<?php
							foreach($this->cotizacion_listado as $cotizacion){
								echo '<option value="'.$cotizacion['id'].' " >'.$cotizacion['folio'].'</option>';
							}
						?>
					</select>
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