var ConfigCombo=function(){
	var me=this;
	this.configurarComboCampos=function(){
		var me=this;
		
		$(this.selector + ' .formCompConfig select[name="campo_a_mostrar"]').wijcombobox({			
            showTrigger: true,
			width:300,
			minLength:1,
			autoFilter:true,			
			select : function (e, data) {										
				
			},
			search: function (e, obj) { 
				if (obj.datasrc.proxy != undefined ){
					// obj.datasrc.proxy.options.data.filtering = new Array();
					
					// var paramObj=new Array();
						
					// var selector='.formCompConfig select[name="target"]';
					// var selectedIndex = $(selector).wijcombobox('option','selectedIndex');  
					// var selectedItem = $(selector).wijcombobox("option","data");		
					// if (selectedIndex == -1){
						// paramObj['nombreModelo'] ='';
					// }else{
						// if (selectedItem.data == undefined ){
							// paramObj['nombreModelo'] =selectedItem[selectedIndex]['label'];
						// }else{
							// paramObj['nombreModelo'] =selectedItem.data[selectedIndex]['nombre'];
						// }
					// }
					
					
					obj.datasrc.proxy.options.data.fk_catalogo=$(me.selector + ' [name="target"]').val();
				}
                
				
            }
		 });
		 
		 
		 $(this.selector + ' .formCompConfig .cajaComboCampos input[role="textbox"]').bind('focus', function(){			
			if (me.camposEnAjax) return true;			
			me.setDSCampos();
			me.camposEnAjax=true;
		 });
	};
	this.setDSCampos = function(nombreModelo){
		
		
		
		var proxy = new wijhttpproxy({
			url: kore.url_base+kore.modulo+'/catalogos/getCampos', 
			dataType: "json", 
			type:"POST",
			data: { 				
				style: "full",
				fk_catalogo: $(me.selector + ' [name="target"]').val()	
			},
			key: 'rows'
		});

		var myReader = new wijarrayreader([{ 
			name: 'label', 			
			mapping: 'nombre'
		}, {
			name: 'value', 
			mapping: 'id'
		}, { 
			name: 'selected', 
			defaultValue: false
		}]); 

		
		
		var datasource = new wijdatasource({ 
			reader: myReader, 
			proxy: proxy 
		}); 
	
		$(this.selector + ' .formCompConfig select[name="campo_a_mostrar"]').wijcombobox('option','data',datasource);
	};
	this.init=function(selector){
		// alert("SEL2: "+selector);
		this.selector = selector;
		// console.log(this.selector + ' .formCompConfig select[name="target"]');
		$(this.selector + ' .formCompConfig select[name="target"]').wijcombobox({
			select:function(e, data){				
				var selector=me.selector + ' .formCompConfig select[name="campo_a_mostrar"]';
				// console.log("selector"); console.log(selector);
				$(selector).wijcombobox('option','selectedIndex',-1);							
				me.setDSCampos(data.label);
				me.camposEnAjax=true;
			}
		});
		this.configurarComboCampos();
	}
	
}
