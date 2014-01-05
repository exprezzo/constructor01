<style>
	.formCompConfig label{ margin-left:0 !important; }
</style>
<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/config_combo.js"></script>
<?php
	$mod = new catalogoModelo();
	$res=$mod->buscar(array());
	$modelos=$res['datos'];
	// print_r( $this->datos );
	$this->datos['target'] = empty( $this->datos['target'] )? '' : $this->datos['target'];	
?>
<form class="formCompConfig">
	<div>
		<div class="inputBox">
			<label>Eiqueta</label>
			<input name="etiqueta"  class="entradaDatos" value="<?php echo $this->datos['etiqueta']; ?>" />
		</div>
		<div class="inputBox">
			<label>Ayuda</label>
			<input name="ayuda" class="entradaDatos" value="<?php echo $this->datos['ayuda']; ?>" />
		</div>
		<div class="inputBox">
			<label>Requerido</label>
			<input name="requerido"  class="entradaDatos" value="<?php echo $this->datos['requerido']; ?>" />
		</div>
		<div class="inputBox">
			<label>Oculto</label>
			<input name="oculto" class="entradaDatos" value="<?php echo $this->datos['oculto']; ?>" />
		</div>		
		<div class="inputBox" style=""  >
			<label style="">Editable:</label>
			<input type="text" name="editable" class="entradaDatos" style="width:200px;" value="<?php echo $this->datos['editable']; ?>" />
		</div>
		<div class="inputBox" style=""  >
			<label style="">Catalogo:</label>
			<select name="target" class="entradaDatos" style="width:200px;">
				<?php
					foreach($modelos as $mod){
						$selected= ($this->datos['target'] ==$mod['id'] )? 'selected': '';
						echo '<option '.$selected.' value="'.$mod['id'].'" >'.$mod['nombre'].' </option>';
					}
				?>
			</select>		
		</div>				
		<div class="inputBox cajaComboCampos" style=""  >
			<label style="">Campo a Mostrar:</label>
			<select name="campo_a_mostrar" class="entradaDatos" style="width:200px;">				
				<?php
					if ( !empty($this->datos['campo_a_mostrar']) ){
						echo '<option value="'.$this->datos['campo_a_mostrar'].'" >'.$this->datos['campo_a_mostrar'].'</option>';
					}
				?>
			</select>
		</div>
		
	</div>
</form>
<script>
	$( function(){	
		var configCombo=new ConfigCombo();
		var selector='<?php echo $this->selector; ?>';
		// alert("SEL: "+selector);
		configCombo.init(selector);		
	});
</script>