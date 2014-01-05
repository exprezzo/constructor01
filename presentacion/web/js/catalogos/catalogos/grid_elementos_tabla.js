var GridElementosTabla = function(){
	var me=this;
	this.aceptarConfig=function(){
		var paramObj = {};
		
		var selector=me.padre.tabId + '-dialog-configurarComponente form';
		
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
			var selector='.formCompConfig select[name="campo_a_mostrar"]';
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
		this.padre.configBotonesEditada();
		
	};
	this.configurarComponenteSeleccionado=function(){
		
		if ( this.selected == null ) return false;
		
		var componente=this.selected.componente;
		var config=resp=eval('(' + this.selected.comp_config + ')');
		
		me.selected.comp_config
		var params={ 
			componente: componente ,
			config:config
		};
		$("#contenedorDatos2").block({ 
			message: '<h1>Obteniendo Plantilla, espere unos segundos...</h1>'               
		});
		
		$.ajax({
			type: "POST",
			url: kore.url_base+this.padre.configuracion.modulo.nombre+'/'+this.padre.controlador.nombre+'/getConfigurador',
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
			
			$(me.padre.tabId + "-dialog-configurarComponente").wijdialog('open');
			$(me.padre.tabId + "-dialog-configurarComponente").html( resp.datos );
			
			// var datosConfig=eval('(' + me.selected.comp_config + ')');
			
			
			// $.each(datosConfig, function( index, value ) {			  
				// $(me.padre.tabId + "-dialog-configurarComponente form [name='"+index+"']").val(value);
			// });
			
				
				
		});
		
	};
	this.recargar = function(elementos){
		//--------------------		
		var grid=$(this.targetSelector);
		var data=grid.wijgrid('data');
		data.length=0;
		for(var i=0; i<elementos.length; i++){
			data.push(elementos[i]);
		}

		grid.wijgrid('ensureControl', true);
		//-----------------------------
	}
	this.init=function(config){
		var tabId=config.tabId, 
			padre = config.padre, 			
			articulos= new Array();		
		
		
		this.tmp_id			= 0;
		this.tabId			= tabId;
		this.padre			= padre;
		this.target = '#gridConfigTabla';
		this.targetSelector = this.target;		
				
		var params={
			targetSelector:this.target,
			pageSize: 100,
			padre:this
		 };
		var nav= new NavegacionEnTabla();
		
		nav.init(params);
		
		this.configurarGrid(this.targetSelector, articulos);		
		this.configurarToolbar(tabId);		
		var me = this;
		$(this.tabId + "-dialog-confirm-eliminarConcepto").wijdialog({
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
		  
		 $(this.tabId + "-dialog-configurarComponente").wijdialog({
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
		 var id = $(this.tabId + ' [name="id"]').val();
		
		if (id==0){	
		  this.nuevo();
		}
		this.recargar(config.elementos);
		return true;		
	};
	
	this.configurarGrid=function(targetSelector, articulos){		
		var fields=[
			{ name: "id", default:0},
			{ name:'esDefault'},
			{ name:'extras'},
			{ name:'campo'},
			{ name:'llave'},
			{ name:'esNulo'},
			{ name:'tipo'},
			{ name:'componente'},
			{ name:'comp_config', default:'{}'}
			// { name: "eliminado", default:false}
		];
		
		this.fields=fields;			
		var gridElementos=$(targetSelector);						

		var me=this;
		
		gridElementos.bind('keydown', function(e) {
			var code = e.keyCode || e.which;
			code=parseInt( code );
			if(e.keyCode==46 && e.shiftKey){
				me.recuperar();
			}else if(e.keyCode==46){
				$(me.tabId + "-dialog-confirm-eliminarConcepto").wijdialog('open');
			}
		});
		
		gridElementos.wijgrid({
			allowColSizing:true,
			 allowPaging: true,
			pageSize:100,
			allowEditing:true,
			// allowColMoving: false,
			allowKeyboardNavigation:true,
			selectionMode:'singleRow',
			data:articulos,
			rowStyleFormatter: function (args) {
				if (args.data && args.data.eliminado){
					$(args.$rows).addClass("eliminado");
				}
			},
			columns: [
				{ dataKey: "id", visible:true, headerText: "id", editable:true, valueRequired: true },				
				{ dataKey: "esDefault", visible:true, headerText: "valor_default", editable:true, valueRequired: true },
				{ dataKey: "extras", visible:true, headerText: "extra", editable:true, valueRequired: true },
				{ dataKey: "campo", visible:true, headerText: "nombre", editable:true, valueRequired: true },
				{ dataKey: "llave", visible:true, headerText: "llave", editable:true, valueRequired: true },				
				{ dataKey: "esNulo", visible:true, headerText: "permitirNulo", editable:true, valueRequired: true },
				{ dataKey: "tipo", visible:true, headerText: "tipo", editable:true, valueRequired: true },
				{ dataKey: "componente", visible:true, headerText: "Tipo de Componente", editable:true, valueRequired: true },
				{ dataKey: "comp_config", visible:false, headerText: "Config", editable:true, valueRequired: true }				
			]
		});
		var me=this;
		
		gridElementos.wijgrid({ beforeCellEdit: function(e, args) {
				
				var row = args.cell.row() ;
				var index = args.cell.rowIndex();
				var sel=gridElementos.wijgrid('selection');
				sel.addRows(index);
				
				if (args.cell.column().editable === false){
					return false;
				}
				
				var w,h;
				var domCel = args.cell.tableCell();
				w = $(domCel).width() ;
				h = $(domCel).height() ;
				
				var combo=
				$("<input />")
					.val(args.cell.value())
					.appendTo(args.cell.container().empty());
					
				combo.css('width',	w-5 );
				combo.css('height',	h-3 );
				
				args.handled = true;
				combo.focus();
				return true;
			}
		});
				
		gridElementos.wijgrid({cancelEdit:function(){				
				$(targetSelector).wijgrid('ensureControl',true);
			}
		});
		
		gridElementos.wijgrid({ selectionChanged: function (e, args) { 								
			var item=args.addedCells.item(0);						
			var row=item.row();
			var data=row.data;			
			me.selected=data;			
			me.selected.dataItemIndex=row.dataItemIndex;			
			me.selected.sectionRowIndex=row.sectionRowIndex;			
		} });
		
		//corregir bug al expandir/colapsar
		gridElementos.wijgrid({rendered : function (e) {
			 // var w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(1)').width();
			 // $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(1)').width(w);
			 
			 // w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(2)').width();
			 // $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(2)').width(w);
			 
			 // w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(3)').width();
			 // $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(3)').width(w);
			 
			 // w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(4)').width();
			 // $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(4)').width(w);
			 
			 // w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(5)').width();
			 // $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(5)').width(w);
        }});
		
		gridElementos.click(function(){
			
                if($(this).hasClass("ui-icon-triangle-1-e"))
                {
				   gridElementos.wijgrid('endEdit');
					var selectionObj = gridElementos.wijgrid("selection");
				   selectionObj.clear();
                   gridElementos.wijgrid('doRefresh');
				   
                }
				
                else if($(this).hasClass("ui-icon-triangle-1-se"))
                {
					gridElementos.wijgrid('endEdit');
					var selectionObj = gridElementos.wijgrid("selection");
					selectionObj.clear();
                   gridElementos.wijgrid('doRefresh');                   
                }
            });	
		this.numCols=$(targetSelector+' thead th').length;		
		
		
	};
	
	this.recuperar=function(){
		
		var cellInfo= $(this.target).wijgrid("currentCell");
		var row = cellInfo.row();
		var container=cellInfo.container();
		$(this.target+"	tbody tr:eq("+cellInfo.rowIndex()+")").removeClass('eliminado');		
		row.data.eliminado=false;
		$(this.target).wijgrid("ensureControl", true);
		
	}
	this.eliminar=function(){		
		var cellInfo= $(this.target).wijgrid("currentCell");
		
		var row = cellInfo.row();
		var container=cellInfo.container();
		$(this.target + " tbody tr:eq("+cellInfo.rowIndex()+")").addClass('eliminado');		
		row.data.eliminado=true;
		$(this.target).wijgrid("ensureControl", true);
		this.padre.configBotonesEditada();
	}
	this.navegarEnter=function(){		
		this.seleccionarSiguiente(false, true, true);		
	}
	this.seleccionarSiguiente = function(alreves, saltar, mantenerColumna){
		//dos direcciones, hacia atras y hacia adelante.
		//de la ultima caja editable de la fila, pasa a la siguiente fila.
		//si se esta navegando alreves, del primer registro editable, pasa al registro anterior.
		//si no hay otra fila, agrega un nuevo elemento.
		//si está ubicado en el ultimo elemento de la pagina, pasar a la pagina siguiente .
		//si está nvegando alrevés, y está ubicado en el primer elemento de la pagina, pasar a la pagina anterior.
		
		//Obtengo la celda seleccionada
		var tabId, cellInfo, cellIndex, rowIndex,  row, nextCell, nextRow; 
		tabId=this.tabId;
		cellInfo= $(tabId+this.targetSelector).wijgrid("currentCell");
		
		var direccion=	(alreves)? -1 : 1;
		cellIndex=cellInfo.cellIndex();
		rowIndex = cellInfo.rowIndex();
		nextRow=rowIndex;
		nextCell = cellIndex + direccion;
		
		
		if (saltar){
			nextCell=(alreves)? -1 : this.numCols + 1			
		}
		
		if ( nextCell<0 ){
			//ir al registro anterior, cambiar de pagina
			row=cellInfo.row();
			var data = $(tabId+this.targetSelector).wijgrid('data');
			var pageSize = $(tabId+this.targetSelector).wijgrid('option','pageSize');
			var pageIndex = $(tabId+this.targetSelector).wijgrid('option','pageIndex');
			
			dataItemIndex = row.dataItemIndex;
			var fi= (pageSize * pageIndex);
						
			if ( dataItemIndex == fi){
				if (pageIndex==0){
					return false;
				}
				$(tabId+this.targetSelector).wijgrid('option','pageIndex',pageIndex-1);
				nextCell=0;
				nextRow=pageSize*2;
			}
			
			nextCell=this.numCols-1;
			nextRow	= nextRow - 1;
			
			var cell;

			if (nextCell>-1 && nextRow>-1){
				while (true)
				 {
					cell = $(tabId+this.targetSelector).wijgrid('currentCell',nextCell, nextRow);
					if (cell.column == undefined ){
						nextRow--;
					}else{					
						break;
					}
				}			
			}else{
				return false;
			}
		} else if ( nextCell>=this.numCols || saltar){
			nextCell=0;
			if (mantenerColumna){
				
				nextCell=cellIndex;
			}
			//ir al registro siguiente, cambiar de pagina o agregar nuevo registro,
			row=cellInfo.row();			
			var data = $(tabId+this.targetSelector).wijgrid('data');			 
			var pageSize = $(tabId+this.targetSelector).wijgrid('option','pageSize');
			var pageIndex = $(tabId+this.targetSelector).wijgrid('option','pageIndex');			 
			//voy a ver si es el ultimo registro de la pagina
			dataItemIndex = row.dataItemIndex;
			var ip= (pageSize * (pageIndex+1) )-1;
			// var index = collection.indexOf(0, 0);
			
			
			if ( (dataItemIndex+1) == data.length ){
				//esta en el ultimo registro de la ultima pagina
				//agregar nuevo, si esta al final de la pagina, despues de agregar registro, mover a la siguiente pagina
				var rec={};
				$.each( this.fields, function(indexInArray, valueOfElement){
					var campo=valueOfElement.name;
					rec[campo]='';				
				} );
				data.push(rec);
				//
				$(tabId+this.targetSelector).wijgrid("ensureControl", true);
				$(tabId+this.targetSelector).wijgrid('option','pageIndex',pageIndex+1);
			}else if ( ip==dataItemIndex ){
				//esta al final de la pagina, cambiar de página
				nextCell=0;
				nextRow=-1;
				$(tabId+this.targetSelector).wijgrid('option','pageIndex',pageIndex+1);				
			}
						
			nextRow	= nextRow + 1;			
			var cell;
			
			while (true)
			 {
				cell = $(tabId+this.targetSelector).wijgrid('currentCell',nextCell, nextRow);
				if (cell.column == undefined ){
					nextRow++;
				}else{						
					break;
				}
			}
			
		}
		
		
		var nuevo = $(tabId+this.targetSelector).wijgrid("currentCell",nextCell, nextRow);
		
		if ( nuevo.column().editable===false ){
			this.seleccionarSiguiente(alreves);
		}else{			
			$(tabId+this.targetSelector).wijgrid("beginEdit");					
		}
		
		
		
	};
	
	this.configurarComboConcepto=function(target){		
		
		
		var tabId=this.tabId;
		var me=this;
		var fields=[										
			{name: 'precio'},
			{name: 'unidad'},
			{name: 'fk_unidad'},
			{name: 'impuestos'},
			{name: 'label', mapping: 'nombre'}, 
			{name: 'value', mapping: 'id'},
			{name: 'selected',defaultValue: false}
		];
		
		var myReader = new wijarrayreader(fields);
		
		var proxy = new wijhttpproxy({
			url: kore.url_base+'conceptos/buscarFull',
			dataType:"json",
			type:'POST'
		});
		
		var datasource = new wijdatasource({
			reader:  new wijarrayreader(fields),
			proxy: proxy,
			loaded: function (data) {},
			loading: function (dataSource, userData) {                            								
				dataSource.proxy.options.data=dataSource.proxy.options.data || {};				 
				dataSource.proxy.options.data.nombre = (userData) ?  userData.value : '';				 
            }
		});
		
		datasource.reader.read= function (datasource) {			
			var totalRows=datasource.data.totalRows;			
			datasource.data = datasource.data.rows;
			datasource.data.totalRows = totalRows;
			myReader.read(datasource);
		};			
		
		datasource.load();	
		
		var combo=target.wijcombobox({
			data: datasource,
			showTrigger: true,
			minLength: 1,
			forceSelectionText: false,
			autoFilter: true,			
			search: function (e, obj) {},
			select: function (e, item) 
			{		
				me.padre.editado=true;
				var rowdom=$(me.target + ' tbody tr:eq('+me.selected.sectionRowIndex +')');								
				item.precio*=1;				
				
				
				
				// if ( TRASLADADO RETENIDO)
				var traslados=0;
				var retenciones=0;
				
				var impuestos = new Array();
				for(var i=0; i < item.impuestos.length; i++){
					if ( item.impuestos[i].seleccionado == null ) continue;
										
					if ( item.impuestos[i].naturaleza == 'TRASLADADO'){
						traslados+=item.impuestos[i].tasa * 1;						
					}else if ( item.impuestos[i].naturaleza == 'RETENIDO'){
						retenciones+= item.impuestos[i].tasa * 1;						
					}
					
					impuestos.push( item.impuestos[i] );
				}
								 
				item.impuestos = impuestos;
				 rowdom.find('td:eq(3) div').html( '$'+item.precio.formatMoney(2,',','.') );
				 rowdom.find('td:eq(2) div').html( item.unidad );
				 rowdom.find('td:eq(1) div').html( 1 );
				 
				 var importe= item.precio * 1;
				 var retPesos = (retenciones / 100) * importe;
				 var trasPesos = (traslados / 100) * importe;
				 
				 var impPesos = trasPesos - retPesos;
				 item.impPesos = impPesos;
				 item.importe=importe;
				 rowdom.find('td:eq(4) div').html( importe );				 
				 rowdom.find('td:eq(5) div').html( impPesos.formatMoney(4) );
				me.articulo=item;
				me.padre.configBotonesEditada();
				return true;
			}
		});
		combo.focus().select();			
	};
	
	this.configurarComboUM=function(target){		
		
		
		var tabId=this.tabId;
		var me=this;
		var fields=[													
			// {name: 'abreviacion'},
			{name: 'label', mapping: 'abreviacion'}, 
			{name: 'value', mapping: 'id'}, 			
			{name: 'selected',defaultValue: false}
		];
		
		var myReader = new wijarrayreader(fields);
		
		var proxy = new wijhttpproxy({
			url: kore.url_base+'ums/buscar',
			dataType:"json",
			type:'POST'
		});
		
		var datasource = new wijdatasource({
			reader:  new wijarrayreader(fields),
			proxy: proxy,
			loaded: function (data) {},
			loading: function (dataSource, userData) {                            								
				dataSource.proxy.options.data=dataSource.proxy.options.data || {};				 
				dataSource.proxy.options.data.nombre = (userData) ?  userData.value : '';				 
            }
		});
		
		datasource.reader.read= function (datasource) {			
			var totalRows=datasource.data.totalRows;			
			datasource.data = datasource.data.rows;
			datasource.data.totalRows = totalRows;
			myReader.read(datasource);
		};			
		
		datasource.load();	
		
		var combo=target.wijcombobox({
			data: datasource,
			showTrigger: true,
			minLength: 1,
			forceSelectionText: false,
			autoFilter: true,			
			search: function (e, obj) {},
			select: function (e, item) 
			{		
				me.padre.editado=true;
				var rowdom=$(me.target + ' tbody tr:eq('+me.selected.sectionRowIndex +')');								
				
				me.um=item;				
				rowdom.find('td:eq(2) div').html( item.nombre );
				rowdom.find('td:eq(1) div').html( 1 );				
				return true;
			}
		});
		combo.focus().select();			
	};
	
	this.nuevo=function(){	
		
		this.padre.editado=true;
		$(this.target).wijgrid("endEdit");	
		var rec={};
		$.each( this.fields, function(indexInArray, valueOfElement){
			var campo=valueOfElement.name;
			
			
			rec[campo]= (valueOfElement.default != null )? valueOfElement.default : '';		
		} );
		
		
		var nuevo=new Array(rec);
		
		var tabId=this.tabId;		
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
	
	
	
	this.configurarToolbar=function(tabId){
		var me=this;				
		
		$( me.tabId +  " .btnAgregar" )		  
		  .click(function( event ) {
				me.nuevo();			
		});
		
		
		$( me.tabId +  " .btnEliminar" )		  
		  .click(function( event ) {
			
				// me.eliminar();	
				$(me.tabId + "-dialog-confirm-eliminarConcepto").wijdialog('open');
		});
	}
}