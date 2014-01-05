var ConfigTabla=function(){
	var me=this;
	// this.generarTabla=function(catalogo){
		
		/*//---------------------------
		//crear columnas
		//-----------------------------
		*/
	// }
	this.aceptarConfig=function(){
		var paramObj = {};
		
		var selector=me.contenedorSel + '-dialog-configurarComponente form';
		
		$.each($(selector).serializeArray(), function(_, kv) {
		  if (paramObj.hasOwnProperty(kv.name)) {
			paramObj[kv.name] = $.makeArray(paramObj[kv.name]);
			paramObj[kv.name].push(kv.value);
		  }
		  else {
			paramObj[kv.name] = kv.value;
		  }
		});
		//-----------------------------------
		var componente=this.selected.componente;
		if (componente=='Combo Box'){
			var selector=me.contenedorSel+'-dialog-configurarComponente'+ ' .formCompConfig select[name="campo_a_mostrar"]';
			console.log(selector);
			var selectedIndex = $(selector).wijcombobox('option','selectedIndex');  
			var selectedItem = $(selector).wijcombobox("option","data");					
			if (selectedIndex == -1){
				paramObj['campo_a_mostrar'] ='';
			}else{
				if (selectedItem.data == undefined ){
					paramObj['campo_a_mostrar'] =selectedItem[selectedIndex]['label'];
				}else{
					paramObj['campo_a_mostrar'] =selectedItem.data[selectedIndex]['nombre'];
				}
			}
						
		
		}
		
		//-----------------------------------
		var datos=paramObj;
		var strConfig=JSON.stringify(datos);
		
		var cellInfo= $(this.target).wijgrid("currentCell");		
		var row = cellInfo.row();		
		row.data.comp_config=strConfig;
		$(this.target).wijgrid("ensureControl", true);
		
	};
	this.configurarComponente=function(){
		
		if ( this.selected == null ) return false;
		
		var componente=this.selected.componente;
		var config=resp=eval('(' + this.selected.comp_config + ')');
		
		me.selected.comp_config
		var params={ 
			componente: componente ,
			config:config,
			selector:this.contenedorSel+'-dialog-configurarComponente'
		};
		$("#contenedorDatos2").block({ 
			message: '<h1>Obteniendo Plantilla, espere unos segundos...</h1>'               
		});
		
		$.ajax({
			type: "POST",
			url: kore.url_base+'portal/catalogos/getConfigurador',
			data: params
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
			
			resp.datos = (resp.datos==null )? '': resp.datos;
			var msg= (resp.msg)? resp.msg : '';
			var title;
			
			
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
			
			if (resp.success == false)
			$.gritter.add({
				position: 'bottom-left',
				title:title,
				text: msg,
				image: icon,
				class_name: 'my-sticky-class'
			});
			
			$(me.contenedorSel+"-dialog-configurarComponente").wijdialog('open');
			$(me.contenedorSel+"-dialog-configurarComponente").html( resp.datos );
			
			// var datosConfig=eval('(' + me.selected.comp_config + ')');
			
			
			// $.each(datosConfig, function( index, value ) {			  
				// $(me.padre.tabId + "-dialog-configurarComponente form [name='"+index+"']").val(value);
			// });
			
				
				
		});
		
	}
	this.nuevo=function(){	
		
	//	this.padre.editado=true;
		$(this.target).wijgrid("endEdit");	
		var rec={};
		$.each( this.fields, function(indexInArray, valueOfElement){
			var campo=valueOfElement.name;
			
			
			rec[campo]= (valueOfElement.default != null )? valueOfElement.default : '';		
		} );
		
		
		var nuevo=new Array(rec);
		
		
		var data= $(this.target).wijgrid('data');									
		// this.tmp_id++;
		// nuevo[0].tmp_id=this.tmp_id;
		var array3 = data.concat(nuevo); // Merges both arrays
		data.length=0;
		for(var i=0; i<array3.length; i++){
			data.push( array3[i] );
		}

		$(this.target).wijgrid("ensureControl", true);
		$(this.target).wijgrid('option','pageIndex',0);			 
		$(this.target).wijgrid("currentCell", 0, data.length);
		$(this.target).wijgrid("beginEdit");		
	};
	this.configurarGrid=function( catalogo ){
		
		var gridId='#gridConfigTabla';
		
		pageSize=10;
		
		var campos=[
			// { name: "id"  }
			{ name: "campo"},
				{ name: "componente" },
				{ name: "comp_config" },
				{ name: "id" },
				{ name: "esDefault"},
				{ name: "extras"},
				{ name: "llave" },
				{ name: "esNulo"},
				{ name: "tipo" },
				{ name: "fk_catalogo" }
		];
		this.fields=campos;
		var dataReader = new wijarrayreader(campos);
			
		var dataSource = new wijdatasource({
			// proxy: new wijhttpproxy({
				// url: kore.url_base+this.configuracion.modulo.nombre+'/'+this.controlador.nombre+'/buscar',
				// dataType: "json",
				// type:'POST'
			// }),
			dynamic:true,
			reader:new wijarrayreader(campos),
			loading : function(data){				
				// var value = $( ' input[name="query"]').val();				
				// data.proxy.options.data.filtering.push({
					 // dataKey: "nombre",
					 // filterOperator: "Contains",
					 // filterValue: value
				 // });
				// data.proxy.options.data.filtering.push({
					// dataKey: "descripcion",
					// filterOperator: "Contains",
					// filterValue: value
				// });
            }
		});
				
		dataSource.reader.read= function (datasource) {						
			var totalRows=datasource.data.totalRows;						
			datasource.data = datasource.data.rows;
			datasource.data.totalRows = totalRows;
			dataReader.read(datasource);
		};				
		this.dataSource=dataSource;
		var gridBusqueda=$(gridId);

		var me=this;		 
		gridBusqueda.wijgrid({
			dynamic: true,
			allowEditing:true,
			allowColSizing:true,			
			allowKeyboardNavigation:true,
			allowPaging: true,
			pageSize:pageSize,
			selectionMode:'singleRow',
			data:catalogo.elementos,
			showFilter:false,
			rowStyleFormatter: function (args) {
				if (args.data && args.data.eliminado){
					$(args.$rows).addClass("eliminado");
				}
			},
			columns: [	
				
				{ dataKey: "campo", visible:true, headerText: "Elemento" },
				{ dataKey: "llave", visible:true, headerText: "llave" },
				{ dataKey: "componente", visible:true, headerText: "Componente" },
				{ dataKey: "comp_config", visible:false, headerText: "Comp Config" },
				{ dataKey: "id", visible:false, headerText: "id" },
				{ dataKey: "esDefault", visible:false, headerText: "esDefault" },
				{ dataKey: "extras", visible:false, headerText: "extras" },
				
				{ dataKey: "esNulo", visible:false, headerText: "esNulo" },
				{ dataKey: "tipo", visible:false, headerText: "tipo" },
				{ dataKey: "fk_catalogo", visible:false, headerText: "fk_catalogo" }				
			]
		});
		
		var me=this;
		
		gridBusqueda.wijgrid({ selectionChanged: function (e, args) { 					
			var item=args.addedCells.item(0);
			var row=item.row();
			var data=row.data;			
			me.selected=data;			
		} });
		
		gridBusqueda.wijgrid({ loaded: function (e) { 
			$(gridId + ' tr').bind('dblclick', function (e) { 							
				// var pedidoId=me.selected[me.configuracion.pk];				
				// window.location=kore.url_base+me.configuracion.modulo.nombre+'/'+me.controlador.nombre+'/editar/'+pedidoId;
			});			
		} });
	};
	this.cargarCatalogo=function(){
		var fk_catalogo=$('[name="target"]').val();			
		$.ajax({
			type: "POST",
			url: kore.url_base+kore.modulo+'/catalogos/getCatalogo',
			data: { fk_catalogo: fk_catalogo}
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
			
			// me.generarTabla(resp.catalogo);
			var gridId='#gridConfigTabla';
			$(gridId).wijgrid('destroy');
			me.configurarGrid( resp.catalogo );
			
			if (msg != "")
			$.gritter.add({
				position: 'bottom-left',
				title:title,
				text: msg,
				image: icon,
				class_name: 'my-sticky-class'
			});
			
		});
	};
	this.init=function( elementos ){
		this.contenedorSel='#contenedorConfigTabla';
		this.target='#gridConfigTabla';
		
		if ( !$.isArray(elementos) ) elementos= new Array();
		this.configurarGrid( {elementos:elementos})
		$('#actualizarConfigTabla').bind('click', function(){
			
			var elementos=$('#gridConfigTabla').wijgrid('data');
			
			$("[name='config_tabla']").val( JSON.stringify(elementos) );
		});
		// $('select[name="target"]').wijcombobox();
		$('#cargarCatalogo').bind('click', function(){
			me.cargarCatalogo();
			
		});
		this.configurarToolbar();
		$(this.contenedorSel + "-dialog-confirm-eliminarConcepto").wijdialog({
			autoOpen: false,
            captionButtons: {                  
				pin: { visible: false },
				refresh: { visible: false },
				toggle: { visible: false },
				minimize: { visible: false },
				maximize: { visible: false }
			},
			buttons: [{
				text: "Si",
				click: function() {
					  // $("#dialog").jqprint();
					  me.eliminar();
					  $(this).wijdialog("close");
				   }
				},
				{text: "No",
				click: function() {
					  $(this).wijdialog("close");
				   }
				}
			 ]
          });
		  
		  $(this.contenedorSel + "-dialog-configurarComponente").wijdialog({
			autoOpen: false,
			width: 'auto',
            captionButtons: {                  
				pin: { visible: false },
				refresh: { visible: false },
				toggle: { visible: false },
				minimize: { visible: false },
				maximize: { visible: false }
			},
			buttons: [{
				text: "Aceptar",
				click: function() {
					  // $("#dialog").jqprint();
					  me.aceptarConfig();
					  $(this).wijdialog("close");
				   }
				},
				{text: "Cancelar",
				click: function() {
					  $(this).wijdialog("close");
				   }
				}
			 ]
          });
	};	
	this.eliminar=function(){	
		// alert(this.target);
		var cellInfo= $(this.target).wijgrid("currentCell");
		console.log("cellInfo"); console.log(cellInfo);
		var row = cellInfo.row();
		console.log("row"); console.log(row);
		var container=cellInfo.container();
		$(this.target + " tbody tr:eq("+cellInfo.rowIndex()+")").addClass('eliminado');		
		row.data.eliminado=true;
		$(this.target).wijgrid("ensureControl", true);
		// this.padre.configBotonesEditada();
	}
	this.configurarToolbar=function(tabId){
		var me=this;				
		
		$( me.contenedorSel +  " .btnAgregar" )		  
		  .click(function( event ) {
				me.nuevo();			
		});
		
		
		$( me.contenedorSel +  " .btnEliminar" )		  
		  .click(function( event ) {
			
				// me.eliminar();	
				$(me.contenedorSel+"-dialog-confirm-eliminarConcepto").wijdialog('open');
		});
		
		$(me.contenedorSel + ' .btnConfigurar').click( function(){
			me.configurarComponente();
			me.editado=true;
		});
	}
}
