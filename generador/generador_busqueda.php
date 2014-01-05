<?php
class GeneradorBusqueda{
	function generarCodigoFiltros($el){
		//{CAMPOS_BUSQUEDA}
		$config=json_decode($el['comp_config'], true);					
		
		
		if ( strtolower( $el['componente'] ) ==  'combo box' ){
			$visible='false';
			$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
				$modeloObj['nombre'] = $resCat['modelo'];
				$modeloObj['tabla'] = $resCat['tabla'];
				$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
			
			$dataKey =$config['campo_a_mostrar'].'_'.$modeloObj ['nombre'];
			$filtro='
				data.proxy.options.data.filtering.push({
					dataKey: "'.$dataKey.'",
					filterOperator: "Contains",
					filterValue: value
				});
		';
		}else{
			$filtro='
				data.proxy.options.data.filtering.push({
					dataKey: "'.$el['campo'].'",
					filterOperator: "Contains",
					filterValue: value
				});
		';
		}
		
		return $filtro;
	}
	function generarCodigoJs($jsStr, $cat){
		
		$nombreNuevo='var Busqueda'.ucfirst($cat['controlador']).'';
		$jsStr = str_replace('var BusquedaControlador', $nombreNuevo, $jsStr);
		// $jsStr = str_replace('{TITULO NUEVO}', $cat['titulo_nuevo'], $jsStr);
		$jsStr = str_replace('{PREGUNTA-ELIMINAR}', $cat['pregunta_eliminar'], $jsStr);
		// $tituloEdicion='$(tabId +\' #titulo h1\').html('.$cat['titulo_edicion'].');';
		// $jsStr = str_replace('//{TITULO-EDICION}', $tituloEdicion, $jsStr);
		$jsStr = str_replace('{LLAVE-PRIMARIA}', $cat['pk_tabla'], $jsStr);				
		$columnas='';
		$crlf = "\r\n";
		$codigoFiltros='';
		foreach($cat['elementos'] as $el){
			//los componentes tipo tabla se omiten
			if ( strtolower( $el['componente'] ) ==  'tabla' ) continue;
			
			$config=json_decode($el['comp_config'], true);			
			if ($el['llave'] != 'PRI')
			$codigoFiltros .= $this->generarCodigoFiltros($el);
			if ( strtolower( $el['componente'] ) ==  'combo box' ){
				$visible='false';
				$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
				$modeloObj['nombre'] = $resCat['modelo'];
				$modeloObj['tabla'] = $resCat['tabla'];
				$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
		
				$dataKey=$config['campo_a_mostrar'].'_'.$el['campo'];
				$etiqueta=$config['etiqueta'];
				$columnas.='{ dataKey: "'.$dataKey.'", visible:true, headerText: "'.$etiqueta.'" },'.$crlf;
			}else{
				// $visible='true';
				$visible=empty($config['oculto'])? 'true':'false';
			}
			
			if ( strtolower( $el['componente'] ) !=  'tabla' )
			$columnas.='{ dataKey: "'.$el['campo'].'", visible:'.$visible.', headerText: "'.$config['etiqueta'].'" },'.$crlf;
		}
		$columnas=substr($columnas,0, strlen($columnas)-1);
		$jsStr = str_replace('//{COLUMNAS}', $columnas, $jsStr);		
		$jsStr = str_replace('//{CAMPOS_BUSQUEDA}', $codigoFiltros, $jsStr);		
		
		return $jsStr;
	}
	function generarJS($cat, $rutaBase){
		$directorio = $rutaBase.'presentacion/web/js/catalogos/'.$cat['controlador'].'/';
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		$filename = dirname(__FILE__).'/plantillas/busqueda.js';
		$handle = fopen($filename, "r");
		$jsStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$jsStr=$this->generarCodigoJs( $jsStr, $cat );
		//---------------------------------------
		$filename = $directorio.'busqueda.js';
		if ( !file_exists( $filename ) ){
			
			$handle = fopen($filename, "w");
			$jsStr= fwrite($handle, $jsStr, strlen($jsStr));
			fclose($handle);
		}
		//----------------------------------------------
	}
	function generar($cat, $rutaBase){
		// CREAR CONTROLADOR		
		$directorio = $rutaBase.'presentacion/html.php/'.$cat['controlador'].'/';
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		$filename = dirname(__FILE__).'/plantillas/busqueda.php';
		$handle = fopen($filename, "r");
		$conStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$conStr=$this->generarCodigo( $conStr, $cat );
		//---------------------------------------NUEVO
		$filename = $directorio.'busqueda.php';
		if ( !file_exists( $filename ) ){
			$handle = fopen($filename, "w");
			$conStr= fwrite($handle, $conStr, strlen($conStr));
			fclose($handle);
		}
		//----------------------------------------------				
		$res = $this->generarJS($cat, $rutaBase);
		
		return array(
			'success'=>true,
			'msg'=>'Generador:  Busqueda Generada'
		);
	}
	function generarCodigo( $conStr, $cat ){
		$crlf = "\r\n";
		$conStr = str_replace('{TITULO}', $cat['titulo_busqueda'], $conStr);
		$controlador = ucfirst($cat['controlador']);
		$conStr = str_replace('new BusquedaControlador', 'new Busqueda'.$controlador , $conStr);
		
		return $conStr;
	}
	
}
?>