<?php 
class GeneradorModelo{
		
	function generar($cat, $rutaBase){
		$directorio = $rutaBase.'modelos/';		
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		
		
		$filename = dirname(__FILE__).'/plantillas/modelo.php';
		$handle = fopen($filename, "r");
		$modeloStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$attrTabla='var $tabla=\''.$cat['tabla'].'\';';
		$modeloStr = str_replace('//var $tabla;', $attrTabla, $modeloStr);
		
		$attrPK='var $pk=\''.$cat['pk_tabla'].'\';';
		$modeloStr = str_replace('//var $pk;', $attrPK, $modeloStr);
		
		//---------Consulta para obtener el elemento
		$campos='';
		$joins='';		
		$numJoins=0;
		$codigoObtenerElementos='';
		$crlf = "\r\n"; 
		foreach($cat['elementos'] as $el ){
		
			if ( strtolower( $el['componente'] ) ==  'combo box' ){
				$campos.=$cat['modelo'].'.'.$el['campo'].', ';
				
				$config=json_decode($el['comp_config'], true);				 
				$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );

				
				$campos.=$resCat['modelo'].$numJoins.'.'.$config['campo_a_mostrar'].' AS '.$config['campo_a_mostrar'].'_'.$el['campo'].', ';				
				$joins.=$crlf.' LEFT JOIN '.$resCat['tabla'].' AS '.$resCat['modelo'].$numJoins;
				$joins.=' ON '.$resCat['modelo'].$numJoins.'.'.$resCat['pk_tabla'].' = '.$cat['modelo'].'.'.$el['campo'];
				$numJoins++;
			}else  if ( strtolower( $el['componente'] ) ==  'tabla' ){
				// = strtolower( $el['campo'] ) ).'De'.ucfirst( strtolower( $cat['nombre'] ) );
				//------------------------------------------------------------
				$config=json_decode($el['comp_config'], true);
				$fk_catalogo=$config['target'];
				$catMod = new catalogoModelo();
				$cat_obj =$catMod->obtener( array('id'=>$fk_catalogo)  );
				
				$nombreModelo=$cat_obj['modelo'];
				$nombreInstancia= strtolower( $el['campo'] ) .'De'.ucfirst( strtolower( $cat['nombre'] ) );
				
				//------------------------------------------------------------
				
				
				$codigoObtenerElementos.='
				//----------------------------
				$conceptosMod=new '.$nombreModelo.'Modelo();
				$params=array(
					\'filtros\'=>array(
						array(
							\'filterValue\'=>$modelos[0][\''.$cat['pk_tabla'].'\'],
							\'filterOperator\'=>\'equals\',
							\'dataKey\'=>\''.$config['llave_foranea'].'\'
						)
					)
				);
				$'.$nombreInstancia.'=$conceptosMod->buscar($params);				
				$modelos[0][\''.$nombreInstancia.'\'] =$'.$nombreInstancia.'[\'datos\'];
				//---------------------------
				';
			}else{				
				$campos.=$cat['modelo'].'.'.$el['campo'].', ';
				
				
			}
		}
		
		$campos = substr($campos, 0, strlen($campos)-2);
		$camposSelect=$campos;
		$filtros=' WHERE '.$cat['modelo'].'.{CAMPOLLAVE}=:{CAMPOLLAVE}';
		
		
		$sql='SELECT '.$campos.$crlf.
		' FROM '.$cat['tabla'].' AS '.$cat['modelo'].
		$joins.
		$crlf.' '.$filtros;
		$modeloStr = str_replace('{SQL-obtener}', $sql, $modeloStr);
		
		$filtroObtener=' $sth->BindValue(\':{CAMPOLLAVE}\',$llave ); ';
		$modeloStr = str_replace('//{FILTRO-OBTENER}', $filtroObtener, $modeloStr);
		
		$nombreModelo = $cat['modelo'].'Modelo';
		$modeloStr = str_replace('class PlantillaModelo', 'class '.$nombreModelo, $modeloStr);
		
		$modeloStr = str_replace('{CAMPOS-SELECT}', $camposSelect, $modeloStr);
		
		$modeloStr = str_replace('{NOMBRE-MODELO}', $cat['modelo'], $modeloStr);
		$modeloStr = str_replace('// {OBTENER-ELEMENTOS-TABLA}', $codigoObtenerElementos, $modeloStr);
		
		
		//-----------------------------------------------------------------
		// GUARDAR
		$codigoCampos = '';
		$codigoBindCampos='';
		$codigoTablas='';
		foreach($cat['elementos'] as $el ){
			//los componentes tipo tabla se omiten
			
			
			if ( strtolower( $el['componente'] ) ==  'tabla' ){
				$config=json_decode($el['comp_config'], true);				 
				$fk_catalogo=$config['target'];
				$catMod = new catalogoModelo();
				$catObj =$catMod->obtener( array('id'=>$fk_catalogo)  );
				$nombreModelo=$catObj['modelo'];
				$instancia=$el['campo'].'De'.ucfirst($cat['nombre']);
				
				$codigoTablas.='
		$'.$nombreModelo.'Mod = new '.$nombreModelo.'Modelo();
		foreach( $datos[\''.$instancia.'\'] as $el ){
			if ( !empty($el[\'eliminado\']) ){
				if ( !empty($el[\'id\']) ){
					$res = $'.$nombreModelo.'Mod->eliminar( array(\'id\'=>$el[\'id\']) );
					if ($res )$res =array(\'success\'=>true);
				}else{
					$res=array(\'success\'=>true);
				}					
			 }else{
				unset( $el[\'eliminado\'] );
				$el[\''.$config['llave_foranea'].'\']=$idObj;
				// if ( empty($concepto[\'nombre\'])  )  continue;
				$res = $'.$nombreModelo.'Mod->guardar($el);
			 }
			
			
			//-----
			//
			//$res=$'.$nombreModelo.'Mod->guardar($el);
			//if ( !$res[\'success\'] ){											
			//	return $res;
			//}
			
		}';
				continue;
			} 
			
			if ($el['llave']=='PRI' ) {				
				$modeloStr = str_replace('{CAMPOLLAVE}', $el['campo'], $modeloStr);	
				continue;
			}
			
			
			
			$codigoCampos .=' 
		if ( isset( $datos[\''.$el['campo'].'\'] ) ){
			$strCampos .= \' '.$el['campo'].'=:'.$el['campo'].', \';
		}';
			$codigoBindCampos.='
		if  ( isset( $datos[\''.$el['campo'].'\'] ) ){
			$sth->bindValue(\':'.$el['campo'].'\', $datos[\''.$el['campo'].'\'] );
		}';
			
		}
		
		$modeloStr = str_replace('//{guardar()-codigoCampos}', $codigoCampos, $modeloStr);	
		$modeloStr = str_replace('//{guardar()-codigoBindCampos}', $codigoBindCampos, $modeloStr);			
		$modeloStr = str_replace('{guardar()-MSG_NUEVO}', $cat['msg_creado'], $modeloStr);	
		$modeloStr = str_replace('{guardar()-MSG_ACTUALIZADO}', $cat['msg_actualizado'], $modeloStr);	
		$modeloStr = str_replace('//{TABLAS}', $codigoTablas, $modeloStr);	
		
		//---------------------------------------NUEVO
		//nuevo()-ATTRIBUTOS
		$atributos='';
		foreach($cat['elementos'] as $el ){
			//los componentes tipo tabla se omiten
			if ( strtolower( $el['componente'] ) ==  'tabla' ) {
				$nombreCampo=$el['campo'].'De'.$cat['nombre'];
				$atributos.='
			$obj[\''.$nombreCampo.'\']=array();
			';
			}else{
		
				$atributos.='
			$obj[\''.$el['campo'].'\']=\'\';';
				if ( strtolower( $el['componente'] ) ==  'combo box' ){
					$config=json_decode($el['comp_config'], true);		
					
					$fk_catalogo=$config['target'];	
					$catMod=new catalogoModelo();
					$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
					$modeloObj['nombre'] = $resCat['modelo'];
			
					$nombreCampo=$config['campo_a_mostrar'].'_'.$modeloObj['nombre'];
				$atributos.='
			$obj[\''.$nombreCampo.'\']=\'\';';
				
				}
			}
			}
		
		$modeloStr = str_replace('//nuevo()-ATTRIBUTOS', $atributos, $modeloStr);	
		//-----------------------------------------------------------------
		// BUSCAR
		$filtrosBuscar= '';
		$codigoBindCampos='';
		$codigoCampos ='';
		$strCampos='';
		$joins='';
		
		$modeloStr = str_replace('{buscar()-nombreModelo}', $cat['modelo'], $modeloStr);	
		$numJoins=0;
		$strArrCampos='';
		foreach($cat['elementos'] as $el ){			
			//los componentes tipo tabla se omiten
			if ( strtolower( $el['componente'] ) ==  'tabla' ) continue;
			
			$strArrCampos.='\''.$el['campo'].'\', ';
			
			$codigoCampos .=' 
				if ( $filtro[\'dataKey\']==\''.$el['campo'].'\' ) {
					$filtros .= \' '.$cat['modelo'].'.'.$el['campo'].' like :'.$el['campo'].' OR \';
				}';
			$codigoBindCampos.='
			if ( $filtro[\'dataKey\']==\''.$el['campo'].'\' ) {
				$sth->bindValue(\':'.$el['campo'].'\',\'%\'.$filtro[\'filterValue\'].\'%\', PDO::PARAM_STR );
			}';	
			if ( strtolower( $el['componente'] ) ==  'combo box' ){
				
				$config    = json_decode($el['comp_config'], true);				 
				// $fk_modelo = $config['target'];				
				// $modeloMod = new modelocModelo();
				// $modeloObj = $modeloMod->obtener( array('id'=>$fk_modelo)  );
				
				$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
				$modeloObj['nombre'] = $resCat['modelo'];
				$modeloObj['tabla'] = $resCat['tabla'];
				$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
				
				$strArrCampos.='\''.$config['campo_a_mostrar'].'_'.$modeloObj['nombre'].'\', ';
				
				$nombreCampo=$modeloObj['nombre'].$numJoins.'.'.$config['campo_a_mostrar'];
				$nombreFiltro=$config['campo_a_mostrar'].'_'.$modeloObj['nombre'];
				$codigoCampos .=' 
				if ( $filtro[\'dataKey\']==\''.$nombreFiltro.'\' ) {
					$filtros .= \' '.$nombreCampo.' like :'.$nombreFiltro.' OR \';
				}';
				
				$aliasJoin=$modeloObj['nombre'].$numJoins;
				$joins.=$crlf.' LEFT JOIN '.$modeloObj['tabla'].' AS '.$aliasJoin;
				$joins.=' ON '.$aliasJoin.'.'.$modeloObj['llave_primaria'].' = '.$cat['modelo'].'.'.$el['campo'];
				$numJoins++;
				
				$codigoBindCampos.='
			if ( $filtro[\'dataKey\']==\''.$nombreFiltro.'\' ) {
				$sth->bindValue(\':'.$nombreFiltro.'\', \'%\'.$filtro[\'filterValue\'].\'%\', PDO::PARAM_STR );
			}';	
			}
					
		}
		$strArrCampos=substr($strArrCampos, 0, strlen($strArrCampos)-2 );
		$modeloStr = str_replace('//MODELO-CAMPOS', 'var $campos= array('.$strArrCampos.');', $modeloStr);	
		
		$modeloStr = str_replace('//buscar()-FILTROS', $codigoCampos, $modeloStr);	
		$modeloStr = str_replace('//buscar()-BIND_FILTROS', $codigoBindCampos, $modeloStr);			
		$modeloStr = str_replace('{buscar()-JOINS}', $joins, $modeloStr);			
		
		//---------------------------------------NUEVO		
		$directorio = $rutaBase.'modelos/';		
		if ( !file_exists($directorio)) {
			mkdir($directorio);
		}
		
		$filename = $directorio.$cat['modelo'].'.php';
		if ( !file_exists( $filename ) ){
			$handle = fopen($filename, "w");
			$modeloStr= fwrite($handle, $modeloStr, strlen($modeloStr));
			fclose($handle);
		}
		return array(
			'success'=>true,
			'msg'=>'ModGen. Construido.'
		);
	}
	
}
?>