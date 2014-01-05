var ElementosDeCatalogo=function (){	
	//{FUNCIONES-COMBO-TABLA}
	this.init=function(config){
		var tabId=config.tabId, 
			padre = config.padre, 			
			articulos= config.elementos;		
		var target=config.target;
		
		this.config=config;
		
		this.tmp_id			= 0;
		this.tabId			= tabId;
		this.padre			= padre;
		// this.target = tabId+' .tabla_conceptos';
		this.target=config.target;
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
		  
		  
		 var id = $(this.tabId + ' [name="id"]').val();
		
		if (id==0){	
		  this.nuevo();
		}
		return true;		
	};
	
	this.configurarGrid=function(targetSelector, articulos){		
		var fields=[
			//{FIELDS}
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
				//{COLUMNAS}
			]
		});
		var me=this;
		
		
		
		

		gridElementos.wijgrid({rendered : function (e) {
			 var w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(1)').width();
			 $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(1)').width(w);
			 
			 w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(2)').width();
			 $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(2)').width(w);
			 
			 w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(3)').width();
			 $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(3)').width(w);
			 
			 w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(4)').width();
			 $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(4)').width(w);
			 
			 w = $(this.target+' .wijmo-wijgrid thead tr th:nth-child(5)').width();
			 $(this.target+' .wijmo-wijgrid tbody tr td:nth-child(5)').width(w);
        }});
		
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
		
		gridElementos.wijgrid({ beforeCellEdit: function(e, args) {
			switch (args.cell.column().dataKey) {
				//{COMBOS-BEFORE-EDIT}
				default:						
					var domCel = args.cell.tableCell();						
					var w,h;
					w = $(domCel).width() -0;
					h = $(domCel).height() -0;
					var input=$("<input />")
						.val(args.cell.value())
						.appendTo(args.cell.container().empty()).focus().select();							
					input.css('width',	w );
					input.css('height',	h );
					
					
						
					args.handled = true;
					return true;
				break;
			};
		}});
		
		gridElementos.wijgrid({beforeCellUpdate:function(e, args) {
				switch (args.cell.column().dataKey) {
					//{COMBOS-BEFORE-UPDATE}
					default:						
						args.value = args.cell.container().find("input").val();	
						var row=args.cell.row();						
						gridElementos.wijgrid('ensureControl',true);
				}
		}});
		// $(tabId + " .grid_articulos").on("blur", ".wijmo-wijgrid-innercell input" , function(){				
			// $(tabId + " .grid_articulos").wijgrid("endEdit");			
		// });
		gridElementos.wijgrid({ loaded: function (e) { 
					
			$(".wijmo-wijgrid-footer").addClass('dataTables_paginate paging_bootstrap pagination');
		} });
		$(gridElementos).addClass('table table-striped table-bordered table-hover');
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
		cellInfo= $(this.target).wijgrid("currentCell");
		
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
			var data = $(this.target).wijgrid('data');
			var pageSize = $(this.target).wijgrid('option','pageSize');
			var pageIndex = $(this.target).wijgrid('option','pageIndex');
			
			dataItemIndex = row.dataItemIndex;
			var fi= (pageSize * pageIndex);
						
			if ( dataItemIndex == fi){
				if (pageIndex==0){
					return false;
				}
				$(this.target).wijgrid('option','pageIndex',pageIndex-1);
				nextCell=0;
				nextRow=pageSize*2;
			}
			
			nextCell=this.numCols-1;
			nextRow	= nextRow - 1;
			
			var cell;

			if (nextCell>-1 && nextRow>-1){
				while (true)
				 {
					cell = $(this.target).wijgrid('currentCell',nextCell, nextRow);
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
			var data = $(this.target).wijgrid('data');			 
			var pageSize = $(this.target).wijgrid('option','pageSize');
			var pageIndex = $(this.target).wijgrid('option','pageIndex');			 
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
				$(this.target).wijgrid("ensureControl", true);
				$(this.target).wijgrid('option','pageIndex',pageIndex+1);
			}else if ( ip==dataItemIndex ){
				//esta al final de la pagina, cambiar de página
				nextCell=0;
				nextRow=-1;
				$(this.target).wijgrid('option','pageIndex',pageIndex+1);				
			}
						
			nextRow	= nextRow + 1;			
			var cell;
			
			while (true)
			 {
				cell = $(this.target).wijgrid('currentCell',nextCell, nextRow);
				if (cell.column == undefined ){
					nextRow++;
				}else{						
					break;
				}
			}
			
		}
		
		
		var nuevo = $(this.target).wijgrid("currentCell",nextCell, nextRow);
		
		if ( nuevo.column().editable===false ){
			this.seleccionarSiguiente(alreves);
		}else{			
			$(this.target).wijgrid("beginEdit");					
		}
		
		
		
	};
	
	
	
	
	
	this.nuevo=function(){	
		this.padre.editado=true;
		$(this.target).wijgrid("endEdit");	
		var rec={};
		$.each( this.fields, function(indexInArray, valueOfElement){
			var campo=valueOfElement.name;
			rec[campo]='';		
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
		
		$( me.config.contenedor +  " .btnAgregar" )		  
		  .click(function( event ) {
				me.nuevo();			
		});
		
		
		$( me.config.contenedor +  " .btnEliminar" )		  
		  .click(function( event ) {
			
				// me.eliminar();	
				$(me.tabId + "-dialog-confirm-eliminarConcepto").wijdialog('open');
		});
	}
}
