var NavegacionEnAgrupada=function(){	
	this.seleccionarLineaAnterior=function(){
		this.celdaActual.row--;		
		if (this.celdaActual.row<0) this.celdaActual.row=0;

		if ( this.esCeldaEditable() ){
			this.editarCelda({
				col:this.celdaActual.col,
				row: this.celdaActual.row
			});
		}
	};
	this.seleccionarSiguienteLinea=function(){	
		this.celdaActual.row++;		
		
		if (this.celdaActual.row > this.numRows -1){
			this.nuevo();
		}
		
		
		
		

		if ( this.esCeldaEditable() ){
			this.editarCelda({
				col:this.celdaActual.col,
				row: this.celdaActual.row
			});
		}
	};
	
	
	this.nuevo=function(){	
		
		var rec={};
		
		console.log("this.options.padre.fields"); console.log(this.options.padre.fields);
		$.each( this.options.padre.fields, function(indexInArray, valueOfElement){			
			var campo=valueOfElement.dataKey;
			if (campo!=undefined){
				rec[campo]='';		
			}
			
		} );
		
		var nuevo=new Array(rec);
		
		
		// var tabId=this.tabId;
		
		var data= $(this.targetSelector).wijgrid('data');									
		
		this.tmp_id++;
		// nuevo[0].tmp_id=this.tmp_id;
		var array3 = data.concat(nuevo); // Merges both arrays
		data.length=0;
		for(var i=0; i<array3.length; i++){
			data.push( array3[i] );
		}

		//data.slice([]);
		$(this.targetSelector).wijgrid("endEdit");
		$(this.targetSelector).wijgrid("ensureControl", true);
		$(this.targetSelector).wijgrid('option','pageIndex',0);			 
		nuevo = $(this.targetSelector).wijgrid("currentCell", 0, 0);
		// $(this.targetSelector).wijgrid("beginEdit");		
	};
	this.configurarToolbar=function(tabId){
		var me=this;
		
		$(this.tabId+ ' .btnAgregar').click(function(){		
			
			me.nuevo();
			
		});
	}
	this.esCeldaEditable=function(celda){		
		if (celda==undefined){
			var row=this.celdaActual.row, col=this.celdaActual.col;
		}else{
			var row=celda.row, col=celda.col;
		}
		
		
		//Si la celda tiene la clase wijmo-wijgrid-groupheaderrow, editamos con una manera alternativa
		var sel=this.targetSelector+' tbody tr:nth-child('+(row+1)+')';		
		var tr = $(sel);
		
		
		
		if ( tr.hasClass('wijmo-wijgrid-groupheaderrow') ){
			
			var idobjeto = $(tr).attr('idobjeto');
			
			
			if (idobjeto==undefined || idobjeto=='') return false;
			
			/*
			recuerda agregar el dataIndex en el cellStyleFormatter del wijgrid, ver ejemplo:
			
			$("#element").wijgrid({
			cellStyleFormatter: function(args) {
				args.$cell.attr("dataindex",args.column._originalDataKey);
			}
			});
			*/
			
			//obtengo la columna a editar
			var td = tr.find('td:nth-child('+ (col+1) +')');
			
			//con el dataIndex encontraremos la configuracion de la columna.
			var di=td.attr('dataIndex');
			
			//obteno la configuracion del wijgrid
			var columns= $(this.targetSelector).wijgrid('columns');
			
			//ahora voy a obtener la propiedad groupEditable de la columna en cuestión
			var options;
			
			for(var $i=0; $i<columns.length; $i++){
				options=columns[$i].options;
				//encontre la configuracion de la columna
				if (options.dataKey == di ){
					if (options.grupoEditable == true ){
						return true;
					}else{
						return false;						
					}
				}				
			 }
			 return false;
		}else{
			$(this.targetSelector).wijgrid("currentCell",  col, row);			
			var current = $(this.targetSelector).wijgrid("currentCell");
						
			var column=current.column();				
				
			var row = current.row();
			
			//con los registros nuevos, la edicion es diferente 
			//----------------------------------------
			if (row.data && row.data.id==undefined || row.data.id=="" || row.data.id == 0 ){
				//En este caso, la propiedad nuevoEditable sobreEscribe readOnly.
				
				if (column.nuevoEditable !== undefined){
					if (column.nuevoEditable === false)  return false;
				}  else{
					if (column.editable === false)  return false;						
				}
				
			}else{
				if (column.editable === false)  return false;
			}
			
			//----------------------------------------			
		}
		return true;		
	};
	
	this.esUltimoRegistro=function(){return false;};
	this.siguientePagina=function(){
		var pi=$(this.targetSelector).wijgrid('option',"pageIndex");
		this.editarPrimero=true;
		$(this.targetSelector).wijgrid('option','pageIndex',pi+1);			 												
	}
	this.paginaAnterior=function(){
		var pi=$(this.targetSelector).wijgrid('option',"pageIndex");
		if (pi<0){
			this.celdaActual.row=0;
			this.celdaActual.col=0;
		}
		this.editarUltimo=true;
		$(this.targetSelector).wijgrid('option','pageIndex',pi-1);			 															
	}
	this.seleccionarCeldaAnterior=function(){
		
		//posicion a la celda anterior.
		this.celdaActual.col--;
		//al llegar ala ultima celda del registro, salta a la primera celda del siguiente registro.
		//si es el ultimo registro de la pagina
		
		//esperen, que pasa si ya estaba en la primera celda?
		if ( this.celdaActual.col < 0 ){
			
			//cambia de registro.
			this.celdaActual.row--; 
			
			//cuando ya estaba en el primer registro, cambia  a la pagina anterior, si no hay, se queda en la primer celda editable
			if ( this.celdaActual.row < 0  ){
				this.celdaActual.col=this.ultimaCelda.col;
				this.celdaActual.row=this.ultimaCelda.row;
				this.editarCelda(this.celdaActual);
				 return false;
				// this.celdaActual.row = this.numRows-1;
				// return this.paginaAnterior();
			}
			
			//Obtiene el numero de columnas del registro recien seleccionado, (ojo, si cambió de pagina no llega a  este punto)
			var rowEl=$(this.targetSelector+' tbody tr:nth-child('+(this.celdaActual.row+1)+')');	
			var tds=rowEl.find('td');
			this.numCols=tds.length;	//<-- Numero de columnas
			
			//se posiciona en la ultima columna
			this.celdaActual.col=this.numCols-1;		
		}
		
		//en este punto ya tenemos una posicion logica del registro a editar, ea!	
		if ( this.esCeldaEditable() ){
			this.editarCelda({
				col:this.celdaActual.col,
				row: this.celdaActual.row
			});
		}else{			
			this.seleccionarCeldaAnterior();
		}
	};
	this.seleccionarSiguienteCelda=function(){
		this.celdaActual.col++;
		//al llegar ala ultima celda del registro, salta a la primera celda del siguiente registro.
		//si es el ultimo registro de la pagina				
		// console.log("this.numCols"); console.log(this.numCols);
		// console.log("this.celdaActual"); console.log(this.celdaActual);
		if ( this.celdaActual.col >= this.numCols ){
			this.celdaActual.col=0;
			this.celdaActual.row++;			
			

			if ( this.celdaActual.row >= this.numRows  ){
				// this.celdaActual.col=this.ultimaCelda.col;
				// this.celdaActual.row=this.ultimaCelda.row;
				// this.editarCelda(this.celdaActual);
				// alert("nuevo");
				 this.nuevo();
				 // return false;
				// return this.siguientePagina();
			}
			
			// var tr=$(this.targetSelector+' tbody tr');					
			// var numRows=tr.length;	
						
			// if (this.celdaActual.row >= numRows){
				// this.celdaActual.row = numRows-1;
				// this.celdaActual.col = this.numCols-1;
			// }
			
		}
		
		
		if ( this.esCeldaEditable() ){
			this.editarCelda({
				col:this.celdaActual.col,
				row: this.celdaActual.row
			});
		}else{			
			this.seleccionarSiguienteCelda();
		}
	};
	this.editarGrupo=function(iCol, iRow){		
		$(this.targetSelector).wijgrid('endEdit');				
		
		
		var tr=$(this.targetSelector+' tbody tr:nth-child('+(iRow+1)+')');		
		// var prodId=tr.attr('idobjeto');		
		var prodId="fakeId";
		var td=tr.find('td:nth-child('+(iCol+1)+')');				
		
		var div=td.find('div');
		
		var input=$("<input style='text-align:right;' />");
			input.val( div[0].innerText ) 
			.appendTo(  td.find('div').empty() ).focus().select();			
		// jQuery.data( input, 'celda', args.target )
		input.focus();
		
		input.bind('change',function(){
			var index=prodId.toString();
			
			
			if ( me.padre.prods==undefined ){
				me.padre.prods={};
			}
			if ( me.padre.prods[index] == undefined ){
				me.padre.prods[index]={};
			}
							
			var di=$(args.target).parent().attr('dataIndex');					
			me.padre.prods[index][di]= $(this).val();				
			
			// $(me.tabId+' .grid_articulos').wijgrid('doRefresh');
		});
		
		
		input.bind('blur',function(){			
			var input=$(this);							
			input.parent().empty().html( input.val() );
						
			
		});
	}
	this.editarCelda=function(celda){
		var row=celda.row, col=celda.col;				
		//Si la celda tiene la clase wijmo-wijgrid-groupheaderrow, editamos con una manera alternativa
		var sel=this.targetSelector+' tr:nth-child('+(row+1)+')';		
		var tr = $(sel);
		
		
		
		if ( tr.hasClass('wijmo-wijgrid-groupheaderrow') ){
			this.ultimaCelda={
				col:col,
				row:row
			};
			 this.editarGrupo(col, row);
		}else{
			console.log("celda"); console.log(celda);
			this.ultimaCelda={
				col:col,
				row:row
			};
			$(this.targetSelector).wijgrid("currentCell",  col, row);
			$(this.targetSelector).wijgrid("beginEdit");
		}
		
		
	};
	
	this.reset=function(){
		var me=this;
		$(this.targetSelector+' tbody tr td').bind('keydown', function(e) {
			
			var code = e.keyCode || e.which;
			code=parseInt(code);
			//ubicacion de la celda
			var col = $(this).parent().children().index( $(this) );
			var row = $(this).parent().parent().children().index( $(this).parent() );
			
			var rowEl=$(me.targetSelector+' tbody tr:nth-child('+(row+1)+')');	
			var tds=rowEl.find('td');
			me.numCols=tds.length;
		
			var celdaActual={
				col:col,
				row:row
			};
			
			
			me.celdaActual = celdaActual;
			
				
			if(e.keyCode==46){
				// me.eliminar();
			}else if(e.keyCode==13 && e.shiftKey){	
				//Saltar al siguiente registro				
				e.preventDefault();			
				e.stopPropagation();
				me.seleccionarLineaAnterior();				
			}else if(e.keyCode==13){	
				//Saltar al siguiente registro
				e.stopPropagation();
				e.preventDefault();							
				me.seleccionarSiguienteLinea();
				
			}else if(e.keyCode==9  && e.shiftKey){	
				e.preventDefault();	
				e.stopPropagation();				
				me.seleccionarCeldaAnterior();				
			}else if(e.keyCode==9 ){				
				e.preventDefault();	
				e.stopPropagation();
							
				me.seleccionarSiguienteCelda();
			}
			
			var tr=$(me.targetSelector+' tbody tr');					
			me.numRows=tr.length;							
		});
	}
	this.init=function(params){
		this.options=params;
		
		
		this.tablaSel=params.targetSelector;
		this.targetSelector =params.targetSelector;
		this.pageSize =params.pageSize;
		var me=this;
		this.tabId=params.tabId;
		// var tr=$(this.targetSelector+' thead tr:nth-child(1)');		
		// var tds=tr.find('th');		
		
		
		
		
		$(this.targetSelector).bind("wijgridloaded", function (e) { 
			
			if (me.editarPrimero){
				me.editarPrimero=false;				
				$(me.targetSelector).wijgrid("currentCell",  me.celdaActual.col, me.celdaActual.row);				
				$(me.targetSelector).wijgrid("beginEdit");
			}
			if (me.editarUltimo){
				me.editarUltimo=false;				
				$(me.targetSelector).wijgrid("currentCell",  me.celdaActual.col, me.celdaActual.row);				
				$(me.targetSelector).wijgrid("beginEdit");
			}
			
			var tr=$(me.targetSelector+' tbody tr');					
			me.numRows=tr.length;							

			me.reset();
		});
		
		
		
		// $(params.targetSelector+' tbody tr').delegate('td','keydown', function(e) {
		this.configurarToolbar();
		this.reset();
		// $(params.targetSelector+' tbody tr td').unbind('keydown');
		
		
		
	}
	
}