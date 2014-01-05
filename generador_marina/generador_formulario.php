<?php
class GeneradorFormulario{
	function generarCodigoBeforeEditCombo($el){
		
		$elTablaConfig=json_decode( $el['comp_config'], true ); 
		
		return '
			case "'.$elTablaConfig['campo_a_mostrar'].'_'.$el['campo'].'":
				var w,h;
				var domCel = args.cell.tableCell();
				w = $(domCel).width() ;
				h = $(domCel).height() ;
				
				var combo=
				$("<input />")
					.val(args.cell.value())
					.appendTo(args.cell.container().empty());
					
				combo.css(\'width\',	w-5 );
				combo.css(\'height\',	h-7 );
				
				args.handled = true;
				
				me.configurarCombo'.ucfirst($el['campo']).'(combo);						
			break;';
	}
	
	function generarCodigoBeforeUpdateCombo($el){
		$elTablaConfig=json_decode( $el['comp_config'], true ); 
		
		$fk_catalogo=$elTablaConfig['target'];	
		$catMod=new catalogoModelo();
		$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
		
		return '
			case "'.$elTablaConfig['campo_a_mostrar'].'_'.$el['campo'].'":
				args.value = args.cell.container().find("input").val();

				if (me.'.$resCat['modelo'].'!=undefined){
					var row=args.cell.row();					
					row.data.'.$el['campo'].' = me.'.$resCat['modelo'].'.value;					
					gridElementos.wijgrid(\'ensureControl\',true);					
				}
				break;';
	}
	function generarFuncionComboTabla($el){
		// print_r( $el );
		$elTablaConfig=json_decode( $el['comp_config'], true ); 
		$fk_catalogo=$elTablaConfig['target'];	
		$catMod=new catalogoModelo();
		$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
		$campos='';
		$crlf = "\r\n"; 
		 // print_r($resCat); exit;
		foreach($resCat['elementos'] as $elem ){
			if ( $elem['llave']=='PRI' ){
				$campos.='
			{name:\'value\',mapping: \''.$elem['campo'].'\' }, ';					
			}else if( $elem['campo'] == $elTablaConfig['campo_a_mostrar'] ){
				$campos.='
			{name:\'label\',mapping: \''.$elem['campo'].'\' }, ';	
			}else{
				$campos.='
			{name: \''.$elem['campo'].'\' }, ';					
			}
			
		}
		$campos= substr($campos,0, strlen($campos) -2 );
		return '
	this.configurarCombo'.ucfirst($el['campo']).'=function(target){		
		var tabId=this.tabId;
		var me=this;
		var fields=[										
			'.$campos.'
		];
		
		var myReader = new wijarrayreader(fields);
		
		var proxy = new wijhttpproxy({
			url: kore.url_base+kore.modulo+\'/'.$resCat['controlador'].'/buscar\',
			dataType:"json",
			type:\'POST\'
		});
		
		var datasource = new wijdatasource({
			reader:  new wijarrayreader(fields),
			proxy: proxy,
			loaded: function (data) {},
			loading: function (dataSource, userData) {                            								
				dataSource.proxy.options.data=dataSource.proxy.options.data || {};				 
				dataSource.proxy.options.data.nombre = (userData) ?  userData.value : \'\';				 
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
				me.'.$resCat['modelo'].'=item;
				
				return true;
			}
		});
		combo.focus().select();			
	};';
	}
	function generarCodigoGuardarCombo( $el ){
		$config=json_decode($el['comp_config'], true);
		$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
				$modeloObj['nombre'] = $resCat['modelo'];
				$modeloObj['tabla'] = $resCat['tabla'];
				$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
		
		$campo=$el['campo'];
		$codigo='
		//-----------------------------------		
		var selectedIndex = $(\'[name="'.$campo.'"]\').wijcombobox(\'option\',\'selectedIndex\');  
		var selectedItem = $(\'[name="'.$campo.'"]\').wijcombobox("option","data");		
		if (selectedIndex == -1){
			paramObj[\''.$campo.'\'] =0;
		}else{
			if (selectedItem.data == undefined ){
				paramObj[\''.$campo.'\'] =selectedItem[selectedIndex][\'value\'];
			}else{
				paramObj[\''.$campo.'\'] =selectedItem.data[selectedIndex][\''.$modeloObj['llave_primaria'].'\'];
			}
		}
		//-----------------------------------
		';
		
		return $codigo;
	}
	function generarInitCombo( $el ){
		$config=json_decode($el['comp_config'], true);
		$fk_catalogo=$config['target'];	
		$catMod=new catalogoModelo();
		$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
		$modeloObj['nombre'] = $resCat['modelo'];
		$modeloObj['tabla'] = $resCat['tabla'];
		$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
		
		$nombreModelo=ucfirst($modeloObj['nombre']);
		$codigo='this.configurarCombo'.ucfirst($el['campo']).'();';
		return $codigo;
	}
	function generarCodigoCombo( $el, $cat ){
		$config=json_decode($el['comp_config'], true);
		$fk_catalogo=$config['target'];	
		$catMod=new catalogoModelo();
		$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
		$modeloObj['nombre'] = $resCat['modelo'];
		$modeloObj['tabla'] = $resCat['tabla'];
		$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
				
		$modelo=ucfirst ( $modeloObj['nombre'] );
		$modelo_min=strtolower( $modeloObj['nombre'] );
		$claseContenedor='contenedor_'.$el['campo'];
		$campo = $el['campo'];
		
		$crlf = "\r\n"; 
		$camposCombo='
		{name:\'label\', mapping:\''.$config['campo_a_mostrar'].'\' }, ';
		$camposCombo.='
		{name:\'value\', mapping:\''.$modeloObj['llave_primaria'].'\' }';
	
		
		$configurarCombo='
	this.configurarCombo'.ucfirst($el['campo']).'=function(){
		var me=this;
		
		$(\'select[name="'.$campo.'"]\').wijcombobox({			
			showTrigger: true,
			width:300,
			minLength:1,
			autoFilter:false,	
			forceSelectionText:true,
			select : function (e, data) {						
			},
			search: function (e, obj) { 						
			}
		 });
		 
		 $(\'.'.$claseContenedor.' input[role="textbox"]\').bind(\'keypress\', function(){			
			if (me.'.ucfirst($el['campo']).'EnAjax) return true;			
			me.setDS'.ucfirst($el['campo']).'();
			me.'.ucfirst($el['campo']).'EnAjax=true;
		 });
	};
		
		';
		$setDs='
	this.setDS'.ucfirst($el['campo']).' = function(){		
		
		var filtering=new Array();
		var proxy = new wijhttpproxy({
			url: kore.url_base+kore.modulo+\'/'.$cat['controlador'].'/buscar'.$modelo.'\',
			dataType: "json", 
			type:"POST",
			data: {
				style: "full",
				 filtering:filtering						
			},
			key: \'datos\'
		}); 

		var myReader = new wijarrayreader(['.$camposCombo.']); 

		var datasource = new wijdatasource({ 
			reader: myReader, 
			proxy: proxy 
		}); 
	
		$(\'select[name="'.$campo.'"]\').wijcombobox(\'option\',\'data\',datasource);
	};
		';
		return $configurarCombo.$setDs;
	}
	function generarCodigoJs($jsStr, $cat){
		
		$nombreNuevo='var Edicion'.ucfirst($cat['controlador']).' = function(){';
		$jsStr = str_replace('var EdicionControlador = function(){', $nombreNuevo, $jsStr);
		
		$jsStr = str_replace('{TITULO NUEVO}', $cat['titulo_nuevo'], $jsStr);
		$jsStr = str_replace('{PREGUNTA-ELIMINAR}', $cat['pregunta_eliminar'], $jsStr);
		
		$cadenaAremplazar = $cat['titulo_edicion'];
		 // echo $cadenaAremplazar; 
		// \{(.*?)\}
		preg_match_all  ('/\{(.*?)\}/',$cadenaAremplazar, $matches);
		// print_r($matches); exit;
		if ($matches){
			for($im=0; $im<sizeof($matches[0]); $im++) {
				$cadena = $matches[0][$im];
				$cadenaSin= '\' + getValorCampo(\''.$matches[1][$im].'\') + \'';
				
				$cadenaAremplazar = str_replace($cadena, $cadenaSin, $cadenaAremplazar);
				
			}
			
		}
		$cadenaAremplazar='\''.$cadenaAremplazar.'\'';
		
		// echo  $cadenaAremplazar; exit;
		$tituloEdicion='$(tabId +\' #titulo h1\').html('.$cadenaAremplazar.');';
		$jsStr = str_replace('//{TITULO-EDICION}', $tituloEdicion, $jsStr);
		
		
		$jsStr = str_replace('{LLAVE-PRIMARIA}', $cat['pk_tabla'], $jsStr);
		
		$funcionesCombo='';
		$funcionesInitCombo='';
		$codigoGuardarCombo='';
		$crlf = "\r\n"; 
		$codigoDatosTabla='';
		$cargarTablas='';
		foreach($cat['elementos'] as $el){
			if ( strtolower( $el['componente'] ) ==  'combo box' ){
				
				$funcionesCombo.= $this->generarCodigoCombo( $el, $cat );
				$funcionesInitCombo.=$crlf.$this->generarInitCombo( $el, $cat  );
				$codigoGuardarCombo.=$crlf.$this->generarCodigoGuardarCombo( $el );
			}else if ( strtolower( $el['componente'] ) ==  'tabla' ){
				$clase='tabla_'.$el['campo'];
				$instancia=$el['campo'].'De'.ucfirst($cat['nombre']);
				$codigoDatosTabla.='$(tabId+\' .'.$clase.'\').wijgrid(\'endEdit\');
				var '.$el['campo'].'=$(tabId+\' .'.$clase.'\').wijgrid(\'data\');
				datos.'.$instancia.' = '.$el['campo'].';';
				
				$cargarTablas='
				//--------------------
				var elementos=resp.datos.'.$instancia.';	

				var grid=$(me.tabId+" .tabla_'.$el['campo'].'");
				var data=grid.wijgrid(\'data\');				
				data.length=0;
				for(var i=0; i<elementos.length; i++){
					data.push(elementos[i]);
				}

				grid.wijgrid(\'ensureControl\', true);
				//-----------------------------
				';
				
			}
			
		}
		$jsStr = str_replace('//FUNCIONES-COMBO', $funcionesCombo, $jsStr);
		$jsStr = str_replace('//{INIT-COMBOS}', $funcionesInitCombo, $jsStr);
		$jsStr = str_replace('//{CODIGO-GUARDAR-COMBOS}', $codigoGuardarCombo, $jsStr);
		$jsStr = str_replace('//{GUARDAR-TABLAS}', $codigoDatosTabla, $jsStr);
		$jsStr = str_replace('//{CARGAR-TABLAS}', $cargarTablas, $jsStr);
		
		
		return $jsStr;
	}
	function generarJS($cat, $rutaBase){
		$directorio = $rutaBase.'presentacion/web/js/catalogos/'.$cat['controlador'].'/';
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		$filename = dirname(__FILE__).'/plantillas/edicion.js';
		$handle = fopen($filename, "r");
		$jsStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$jsStr=$this->generarCodigoJs( $jsStr, $cat );
		//---------------------------------------
		$filename = $directorio.'edicion.js';
		if ( !file_exists( $filename ) ){
			$handle = fopen($filename, "w");
			$jsStr= fwrite($handle, $jsStr, strlen($jsStr));
			fclose($handle);
		}
		//----------------------------------------------
	}
	
	
	function generar($cat, $rutaBase){
		// CREAR HTML
		$directorio = $rutaBase.'presentacion/html.php/'.$cat['controlador'].'/';
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		$filename = dirname(__FILE__).'/plantillas/formulario.php';
		$handle = fopen($filename, "r");
		$htmlStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$htmlStr=$this->generarHtml( $htmlStr, $cat, $rutaBase );
		//---------------------------------------
		$filename = $directorio.'edicion.php';
		
		//antes de escribir el html, revisa que no exista
		if ( !file_exists( $filename ) ){
			$handle = fopen($filename, "w");
			$htmlStr= fwrite($handle, $htmlStr, strlen($htmlStr));
			fclose($handle);
		}
		
		//----------------------------------------------
		$res = $this->generarJS($cat, $rutaBase);
		
		return array(
			'success'=>true,
			'msg'=>'FormGem. Generado '
		);
	}
	function getStrRelacion($modeloObj, $el, $config, $resCat){
		$campo_a_mostrar = $config['campo_a_mostrar'];
		
		$campo=$el['campo'];
		$campo_dato = $config['campo_a_mostrar'].'_'.$campo;
		
		$strRelacion='if ( !empty( $this->datos[\''.$resCat['pk_tabla'].'\'] ) ){
			
			$'.$campo.'_listado=array();
			$'.$campo.'_listado[]=array(\''.$modeloObj['llave_primaria'].'\'=>$this->datos[\''.$el['campo'].'\'],\''.$campo_a_mostrar.'\'=>$this->datos[\''.$campo_dato.'\'] );
			$this->'.$campo.'_listado = $'.$campo.'_listado;
		}else{
			$mod=new '.$modeloObj['nombre'].'Modelo();
			$objs=$mod->buscar( array() );		
			$this->'.$campo.'_listado = $objs[\'datos\'];
		}';
		return $strRelacion;
	}
	
	function generarHtml($htmlStr, $cat, $rutaBase){
		$tituloNuevo=$cat['titulo_nuevo'];
		$htmlStr = str_replace('<!--{TITULO-NUEVO}-->', $tituloNuevo, $htmlStr);

		$controlador=ucfirst ( $cat['controlador'] );
		$nuevaCadena = 'var editor=new Edicion'.$controlador.'();';
		$htmlStr = str_replace('var editor=new EdicionControlador();', $nuevaCadena, $htmlStr);
		
		
		//---------Consulta para obtener el elemento
		$campos='';		
		$crlf = "\r\n"; 
		$strRelaciones='';
		$configComponentes='';
		$scriptsComponentes='';
		foreach($cat['elementos'] as $el ){
			$config=json_decode($el['comp_config'], true);				 
			
			if ( strtolower( $el['componente'] ) ==  'combo box' ){				
				
				$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
				$modeloObj['nombre'] = $resCat['modelo'];
				$modeloObj['tabla'] = $resCat['tabla'];
				$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
				
				
				$strRelaciones.=$crlf.$this->getStrRelacion($modeloObj, $el, $config, $resCat);
				
				// $campos.=$modeloObj['nombre'].$numJoins.'.'.$config['campo_a_mostrar'].' AS '.$config['campo_a_mostrar'].'_'.$modeloObj['nombre'].', ';				
				// $joins.=$crlf.' LEFT JOIN '.$modeloObj['tabla'].' AS '.$modeloObj['nombre'].$numJoins;
				// $joins.=' ON '.$modeloObj['nombre'].$numJoins.'.'.$modeloObj['llave_primaria'].' = '.$cat['modelo'].'.'.$el['campo'];
				// $numJoins++;
				
				
				$clase='contenedor_'.$el['campo'];
				if ( !empty($config['oculto']) ) $clase.=' oculto';
				$campos.='
				<div class="inputBox '.$clase.'" style=""  >
					<label style="">'.$config['etiqueta'].':</label>
					<select name="'.$el['campo'].'" class="entradaDatos" style="width:250px;">
						<?php
							foreach($this->'.$el['campo'].'_listado as $'.$modeloObj['nombre'].'){
								echo \'<option value="\'.$'.$modeloObj['nombre'].'[\''.$modeloObj['llave_primaria'].'\'].\' " >\'.$'.$modeloObj['nombre'].'[\''.$config['campo_a_mostrar'].'\'].\'</option>\';
							}
						?>
					</select>
				</div>';
			}else if ( strtolower( $el['componente'] ) ==  'tabla' ){				
				$config=json_decode($el['comp_config'], true);
				//-----
				$nombreArchivo=$el['campo'].'_de_'.$cat['nombre'];
				$nombreArchivo=strtolower($nombreArchivo);			
				$nombreClaseJs=ucfirst( strtolower( $el['campo'] ) ).'De'.ucfirst( strtolower( $cat['nombre'] ) );				
				//--------------------------------------------
				$fk_catalogo=$config['target'];				
				$catMod = new catalogoModelo();
				$catObj =$catMod->obtener( array('id'=>$fk_catalogo)  );
				//--------------------------------------------
				$directorio = $rutaBase.'presentacion/web/js/catalogos/'.$cat['controlador'].'/';
				if ( !file_exists($directorio)) {
					mkdir($directorio,'0777', true);
				}
				$filename = dirname(__FILE__).'/plantillas/elementos_de_catalogo.js';
				$handle = fopen($filename, "r");
				$jsStr= fread($handle, filesize($filename));
				fclose($handle);
				//------------------------------------------
				$jsStr = str_replace('ElementosDeCatalogo', $nombreClaseJs, $jsStr);
				$jsStr = str_replace('-dialog-confirm-eliminarConcepto', '-dialog-confirm-eliminar-'.$catObj['modelo'], $jsStr);
				$camposTabla='';
				$columnas='';
				$fields='';
				
				$configTabla=json_decode( $config['config_tabla'], true );
				$funcionesComboTabla='';
				$codigoBeforeEditCombo='';
				$codigoBeforeUpdateCombo='';
				foreach($configTabla as $elTabla ){
					
					$elTablaConfig=json_decode( $elTabla['comp_config'], true ); 					 
					$visible=empty($elTablaConfig['oculto'])? 'true':'false';
					if ( strtolower( $elTabla['componente'] ) ==  'combo box' ){		
						$funcionesComboTabla.=$this->generarFuncionComboTabla($elTabla);
						$codigoBeforeEditCombo.=$this->generarCodigoBeforeEditCombo($elTabla);
						$codigoBeforeUpdateCombo .=$this->generarCodigoBeforeUpdateCombo($elTabla);
						
						
						$columnas.='
				{ dataKey: "'.$elTablaConfig['campo_a_mostrar'].'_'.$elTabla['campo'].'", visible:'.$visible.', headerText: "'.$elTablaConfig['etiqueta'].'" },';
				
						$visible='false';
					}
										
					$fields.='
				{ name: "'.$elTabla['campo'].'"},' ;
					
					$columnas.='
				{ dataKey: "'.$elTabla['campo'].'", visible:'.$visible.', headerText: "'.$elTablaConfig['etiqueta'].'" },';
				}
				$columnas=substr($columnas,0, strlen($columnas)-1);
				$fields=substr($fields,0, strlen($fields)-1);
				$jsStr = str_replace('//{COLUMNAS}', $columnas, $jsStr);
				$jsStr = str_replace('//{FIELDS}', $fields, $jsStr);
				$jsStr = str_replace('//{FUNCIONES-COMBO-TABLA}', $funcionesComboTabla, $jsStr);
				$jsStr = str_replace('//{COMBOS-BEFORE-EDIT}', $codigoBeforeEditCombo, $jsStr);
				$jsStr = str_replace('//{COMBOS-BEFORE-UPDATE}', $codigoBeforeUpdateCombo, $jsStr);
				//---------------------------------------
				
				$filename = $directorio.$nombreArchivo.'.js';
				if ( !file_exists( $filename ) ){
					$handle = fopen($filename, "w");
					$jsStr= fwrite($handle, $jsStr, strlen($jsStr));
					fclose($handle);
				}
				//----------------------------------------------
				
				
				$scriptsComponentes.=$crlf.'<script src="<?php echo $_PETICION->url_web; ?>js/catalogos/<?php echo $_PETICION->controlador; ?>/'.$nombreArchivo.'.js"></script>';
				
				$clase='contenedor_tabla_'.$el['campo'];
				$claseTabla='tabla_'.$el['campo'];
				
				$campos.='
				<div class="tabla '.$clase.'" style="position: relative; margin-top: 26px;"  >
					
					<h1 class="tituloTabla" >'.$config['titulo'].'</h1>
					<div class="toolbar_detalles" style="">
						<input type="button" value="" class="btnAgregar" id="botonAgregar"/>
						<input type="button" value="" class="btnEliminar" id="botonEliminar" />
					</div>
					<table class="'.$claseTabla.'">
						<thead></thead>
						<tbody></tbody>
					</table>
					<div id="<?php echo $id; ?>-dialog-confirm-eliminar-'.$catObj['modelo'].'" title="&iquest;Eliminar '.ucfirst($catObj['modelo']).'?">
						<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>&iquest;Eliminar '.ucfirst($catObj['modelo']).'?</p>
					</div> 
				</div>';
				
				
				
				$nombreInstancia = lcfirst ( $nombreClaseJs ); 
				
				$configComponentes.='
		var tabId=\'#\'+config.tab.id;
		configDet={
			padre:editor,
			tabId:\'#<?php echo $_REQUEST[\'tabId\']; ?>\',
			elementos: <?php echo json_encode($this->datos[\''.$nombreInstancia.'\']); ?>,
			target:\'.'.$claseTabla.'\',
			contenedor:\'.'.$clase.'\',
		};

		var '.$nombreInstancia.' = new '.$nombreClaseJs.'();		
		'.$nombreInstancia.'.init(configDet);
				';
				
			}else{
				
				$clase='contenedor_'.$el['campo'];
				if ( !empty($config['oculto']) ) $clase.=' oculto';
				$campos.='
				<div class="inputBox '.$clase.'" style=""  >
					<label style="">'.$config['etiqueta'].':</label>
					<input title="'.$config['ayuda'].'" type="text" name="'.$el['campo'].'" class="entradaDatos" value="<?php echo $this->datos[\''.$el['campo'].'\']; ?>" style="width:500px;" />
				</div>';
			}					
		}
		
		$htmlStr = str_replace('<!-- ELEMENTOS -->', $campos, $htmlStr);
		$htmlStr = str_replace('// {RELACIONES}', $strRelaciones, $htmlStr);
		$htmlStr = str_replace('// {INIT-TABLAS}', $configComponentes, $htmlStr);
		$htmlStr = str_replace('<!-- {SCRIPTS_COMPONENTES} -->', $scriptsComponentes, $htmlStr);
		
		return $htmlStr;
	}	
}
?>