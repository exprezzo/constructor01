
<div class="contenedor_formulario" id="<?php echo $id; ?>">
	<div id="titulo">
    	<h1>Hola Mundo</h1>
	</div>
	<div id="cuerpo" style="padding: 20px 0 20px 0;">						
			<?php
				$mod=getModelo( 'tema' );
				$params=array();
				$modulos = $mod->buscar( $params );
				$apps = $modulos['datos'];				
			?>
			<select id="comboTemas">
				<?php foreach($apps as $app){
					echo '<option value="'.$app['ruta'].'">'.$app['nombre'].'</option>';
				} 
				
				?>
			</select>
		
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#comboTemas').wijcombobox({
			select:function(e, val){
				
				var datos={rutaTema: val.value};
				$('#linkCss').attr('href',val.value);
				$.ajax({
					type: "POST",
					url: kore.url_base+this.configuracion.modulo.nombre+'/paginas/setTema',
					data: { datos: datos}
				}).done(function( response ) {
					$("#contenedorDatos2").unblock(); 
					var resp = eval('(' + response + ')');
					var msg= (resp.msg)? resp.msg : '';
					var title;
					
					if ( resp.success == true	){
						if (resp.msgType!=undefined && resp.msgType == 'info'){
							icon=kore.url_web+'imagenes/yes.png';
						}else{
							icon=kore.url_web+'imagenes/info.png';
						}
						
						if (resp.esNuevo){					
							window.location = kore.url_base+me.configuracion.modulo.nombre+'/'+me.controlador.nombre+'/editar/'+ resp.datos.id;
						}
						title= 'Success';				
						// tab.find('[name="'+me.configuracion.pk+'"]').val(resp.datos[me.configuracion.pk]);
						tab.find('[name="'+me.configuracion.pk+'"]').val(resp.datos[me.configuracion.pk]);
						
						me.actualizarTitulo();
						me.editado=false;
						var objId = '/'+me.configuracion.modulo.nombre+'/'+me.controlador.nombre+'/editar?id='+resp.datos.id;
						objId = objId.toLowerCase();
						$(me.tabId ).attr('objId',objId);				
						
						
						var elementos= ( resp.datos.elementos != null )? resp.datos.elementos : new Array();
						gridElementos.recargar(elementos);
						$.gritter.add({
							position: 'bottom-left',
							title:title,
							text: msg,
							image: icon,
							class_name: 'my-sticky-class'
						});
						
						if (me.saveAndClose===true){
							//busca el indice del tab
							var idTab=$(me.tabId).attr('id');
							var tabs=$('#tabs > div');
							for(var i=0; i<tabs.length; i++){
								if ( $(tabs[i]).attr('id') == idTab ){
									$('#tabs').wijtabs('remove', i);
								}
							}
						}
					}else{
						icon= kore.url_web+'imagenes/error.png';
						title= 'Error';					
						$.gritter.add({
							position: 'bottom-left',
							title:title,
							text: msg,
							image: icon,
							class_name: 'my-sticky-class'
						});
					}
					
					//cuando es true, envia tambien los datos guardados.
					//actualiza los valores del formulario.
					
				});
			}
		})
		
	});
</script>