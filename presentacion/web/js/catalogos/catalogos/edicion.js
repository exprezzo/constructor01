var Edicioncatalogos = function(){
	this.editado=false;
	this.tituloNuevo='Nuevo Catalogo';
	this.saveAndClose=false;
	var gridElementos = new GridElementos();
	this.generar=function(){
		$("#contenedorDatos2").block({ 
			message: '<h1>Generando Codigo del catálogo, espere unos segundos...</h1>'               
		}); 
		
		var id = $("[name='id']").val();
		$.ajax({
			type: "POST",
			url: kore.url_base+this.configuracion.modulo.nombre+'/'+this.controlador.nombre+'/generarCodigo',
			data: { id: id}
		}).done(function( response ) {
			$("#contenedorDatos2").unblock(); 
			var msg, title, icon;
			try{
				var resp = eval('(' + response + ')');
			}catch(err){
				msg='El servidor ha respondido de manera incorrecta. <br />'+response;
				title='Error al generar los archivos';
				icon= kore.url_web+'imagenes/error.png';
				$.gritter.add({
					position: 'bottom-left',
					title:title,
					text: msg,
					image: icon,
					class_name: 'my-sticky-class'
				});
			}
			// var resp = eval('(' + response + ')');
			msg= (resp.msg)? resp.msg : '';
			
			
			if ( resp.success == true	){
				title= 'Exito';					
				icon= kore.url_web+'imagenes/yes.png';				
				//--------------------								
			}else{
				title= 'Error';
				icon= kore.url_web+'imagenes/error.png';
			}
			
			
			var msg='';
			
			//Esto deberia pasar al nucleo
			if (resp.msg != undefined) msg =resp.msg;
			if (resp.title != undefined) title =resp.title;
			// if (resp.icon != undefined){} icon = resp.icon;
			
			$.gritter.add({
				position: 'bottom-left',
				title:title,
				text: msg,
				image: icon,
				class_name: 'my-sticky-class'
			});
			
		});
	}
	this.configBotonesEditada=function(){};
	this.configurarComponente=function(){
		
		gridElementos.configurarComponenteSeleccionado();
	}
	this.recargar=function(){		
		$("#contenedorDatos2").block({ 
			message: '<h1>Obteniendo Campos, espere unos segundos...</h1>'               
		}); 
		
		var tabla = $("[name='tabla']").val();
		$.ajax({
			type: "POST",
			url: kore.url_base+this.configuracion.modulo.nombre+'/'+this.controlador.nombre+'/obtenerCamposDeLaTabla',
			data: { tabla: tabla}
		}).done(function( response ) {
			$("#contenedorDatos2").unblock(); 
			var resp;
			try{
				resp=eval('(' + response + ')');
			}catch(err){
				resp={};
				resp.success = false;
				resp.title='El servidor respondio de manera no apropiada';
				resp.msg=response;
			}
			
			var msg= (resp.msg)? resp.msg : '';
			var title;
			
			
			if ( resp.success == true	){
				title= 'Exito';					
				icon= kore.url_web+'imagenes/yes.png';				
				//--------------------
				var elementos=resp.datos;	
				
				
				var grid=$(gridElementos.targetSelector);
				var data=grid.wijgrid('data');				
				data.length=0;
				for(var i=0; i<elementos.length; i++){
					data.push(elementos[i]);
				}

				grid.wijgrid('ensureControl', true);
				//-----------------------------
			}else{
				title= 'Error';
				icon= kore.url_web+'imagenes/error.png';
			}
			
			
			var msg='';
			
			//Esto deberia pasar al nucleo
			if (resp.msg != undefined) msg =resp.msg;
			if (resp.title != undefined) title =resp.title;
			// if (resp.icon != undefined){} icon = resp.icon;
			
			$.gritter.add({
				position: 'bottom-left',
				title:title,
				text: msg,
				image: icon,
				class_name: 'my-sticky-class'
			});
			
		});
	}
	this.actualizarElementos=function(tabla){
		// var val=$('select[name="tabla"]').val();
		// alert("actualizarElementos: " + tabla);
	};
	this.borrar=function(){		
		var r=confirm("¿Eliminar Elemento?");
		if (r==true){
		  this.eliminar();
		}
	}
	this.activate=function(){
		var tabId=this.tabId;
		
	}
	this.close=function(){
		
		if (this.editado){
			var res=confirm('¿Guardar antes de salir?');
			if (res===true){
				this.saveAndClose=true;
				this.guardar();
				return false;
			}else{
				return true
			}
		}else{
			return true;
		}
	};
	this.init=function(params){
		this.controlador=params.controlador;
		// this.catalogo=params.catalogo;
		this.configuracion=params;
		
		var tabId='#'+params.tab.id;
		var objId=params.objId;
		
		this.tabId= tabId;		
		
		$(tabId+' .cerrar_tab').bind('click', function(){
			TabManager.cerrarTab( params.tab.id );
		 });
		
		var tab=$('div'+this.tabId);
		//estas dos linas deben estar en la hoja de estilos
		tab.css('padding','0');
		tab.css('border','0 1px 1px 1px');
		
		
		this.agregarClase('frm'+this.controlador.nombre);		
		this.agregarClase('tab_'+this.controlador.nombre);
		
		this.configurarFormulario(this.tabId);
		this.configurarToolbar(this.tabId);		
		// this.notificarAlCerrar();			
		this.actualizarTitulo();				
		
		var me=this;
		$(this.tabId + ' .frmEdicion input').change(function(){
			me.editado=true;		
		});
		
		$(tabId+' .toolbarEdicion .boton:not(.btnPrint, .btnEmail)').mouseenter(function(){
			$(this).addClass("ui-state-hover");
		});
		
		$(tabId+' .toolbarEdicion .boton *').mouseenter(function(){						
			 $(this).parent('.boton').addClass("ui-state-hover");						
		});
		
		$(tabId+' .toolbarEdicion .boton').mouseleave(function(e){			 
				$(this).removeClass("ui-state-hover");			
		});
		
		tab.data('tabObj',this); //Este para que?		
		
		
		
		var config={
			tabId:tabId,
			articulos:{},
			elementos:params.elementos,
			padre:this
		};
		gridElementos.init(config);
	};
	//esta funcion pasara al plugin
	//agrega una clase al panel del contenido y a la pesta�a relacionada.
	
	this.agregarClase=function(clase){
		var tabId=this.tabId;		
		var tab=$('div'+this.tabId);						
		tab.addClass(clase);		
		tab=$('a[href="'+tabId+'"]');
		tab.addClass(clase);
	}
	this.notificarAlCerrar=function(){
		var tabId = this.tabId;
		var me=this;
		 $('#tabs > ul a[href="'+tabId+'"] + span').click(function(e){
			e.preventDefault();
			 var tmp=$(me.tabId+' .txtIdTmp');				
			if (tmp.length==1){
				var id=$(tmp[0]).val();				
				$.ajax({
					type: "POST",
					url: '/'+me.configuracion.modulo.nombre+'/'+me.controlador.nombre+'/cerrar',
					data: { id:id }
				}).done(function( response ) {
					
				});
			}	
		 });
	}
	this.actualizarTitulo=function(){
		var me=this;
		function getValorCampo(campo){
			var valor = $(me.tabId + ' [name="'+campo+'"]').val();
			return valor;
		}
		
		var tabId = this.tabId;		
		var id = $(this.tabId + ' [name="'+this.configuracion.pk+'"]').val();
		if (id>0){			
			$(tabId +' #titulo h1').html('Catalogo: ' + getValorCampo('nombre'));
		}else{
			$(tabId +' #titulo h1').html(this.tituloNuevo);
			// $('a[href="'+tabId+'"]').html('Nuevo');
		}
	}
	this.nuevo=function(){
		var tabId=this.tabId;
		var tab = $('#tabs '+tabId);		
		$(tabId +' #titulo h1').html(this.tituloNuevo);
		
		tab.find('.txtId').val(0);
		me.editado=false;
	};	
	this.guardar=function(){
		var tabId=this.tabId;
		var tab = $('#tabs '+tabId);
		var me=this;		
		
		// http://stackoverflow.com/questions/2403179/how-to-get-form-data-as-a-object-in-jquery
		var paramObj = {};
		$.each($(tabId + ' .frmEdicion').serializeArray(), function(_, kv) {
		  if (paramObj.hasOwnProperty(kv.name)) {
			paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
			paramObj[kv.name].push(kv.value);
		  }
		  else {
			paramObj[kv.name] = kv.value;
		  }
		});
		//-----------------------------------
		var datos=paramObj;
		//-----------------------------------------------		
		$(gridElementos.targetSelector).wijgrid('endEdit');		
		var datos=paramObj;		
		var elementos=$(gridElementos.targetSelector).wijgrid('data');
		datos.elementos = elementos;		
		//-----------------------------------------------		
				
		//Envia los datos al servidor, el servidor responde success true o false.
		$("#contenedorDatos2").block({ 
			message: '<h1>Guardando</h1>'               
		}); 
		$.ajax({
			type: "POST",
			url: kore.url_base+this.configuracion.modulo.nombre+'/'+this.controlador.nombre+'/guardar',
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
	};	
	this.eliminar=function(){
		var id = $(this.tabId + ' [name="'+this.configuracion.pk+'"]').val();
		var me=this;
		
		var params={};
		params[this.configuracion.pk]=id;
		
		
		$.ajax({
				type: "POST",
				url: kore.url_base+me.configuracion.modulo.nombre+'/'+me.controlador.nombre+'/eliminar',

				data: params
			}).done(function( response ) {		
				var resp = eval('(' + response + ')');
				var msg= (resp.msg)? resp.msg : '';
				var title;
				if ( resp.success == true	){					
					icon=kore.url_web+'imagenes/yes.png';
					title= 'Success';									
				}else{
					icon= kore.url_web+'imagenes/error.png';
					title= 'Error';
				}
				
				//cuando es true, envia tambien los datos guardados.
				//actualiza los valores del formulario.
				// var idTab=$(me.tabId).attr('id');
				// var tabs=$('#tabs > div');
				me.editado=false;
				// for(var i=0; i<tabs.length; i++){
					// if ( $(tabs[i]).attr('id') == idTab ){
						// $('#tabs').wijtabs('remove', i);
					// }
				// }
				$(me.tabId).find('[name="'+me.configuracion.pk+'"]').val(0);
					
				$.gritter.add({
					position: 'bottom-left',
					title:title,
					text: msg,
					image: icon,
					class_name: 'my-sticky-class'
				});
			});
	},
	

	
	
	this.configurarFormulario=function(tabId){		
		var me=this;
		// $(this.tabId+' .frmEdicion input[type="text"]').wijtextbox();		
		// $(this.tabId+' .frmEdicion textarea').wijtextbox();			
		$('select[name="fk_modulo"]').wijcombobox();
		$('select[name="tabla"]').wijcombobox({
			select:function(e, data){				
				me.actualizarElementos( data.value );
			}
		});
		
	};
	this.configurarToolbar=function(tabId){		
			
			var me=this;
			
			$(this.tabId + ' .toolbarEdicion .btnNuevo').click( function(){
				window.location=kore.url_base+me.configuracion.modulo.nombre+'/'+me.controlador.nombre+'/nuevo';
			});
			
			$(this.tabId + ' .toolbarEdicion .btnGuardar').click( function(){
				me.guardar();
				me.editado=true;
			});
			
			$(this.tabId + ' .toolbarEdicion .btnGenerar').click( function(){
				me.generar();
				me.editado=true;
			});
			
			
			
			$(this.tabId + ' .btnConfigurar').click( function(){
				me.configurarComponente();
				me.editado=true;
			});
			
			$(this.tabId + ' .contenedorTabla .btnRecargarTabla').click( function(){
				me.recargar();
				me.editado=true;
			});
			
			$(this.tabId + ' .toolbarEdicion .btnDelete').click( function(){
				var r=confirm("¿Eliminar?");
				if (r==true){
				  me.eliminar();
				  me.editado=false;
				  me.nuevo();
				}
			});
	};	
}
