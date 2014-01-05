<style>
	.formCompConfig label{ margin-left:0 !important; }
</style>
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
	</div>
</form>