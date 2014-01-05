<?php
class ModuloModelo extends Modelo{	
	var $tabla='system_modulos';
	var $pk='id';
	var $campos= array('id', 'nombre', 'icono', 'nombre_interno', 'ruta_base', 'orden');
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' Modulo2.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='nombre' ) {
					$filtros .= ' Modulo2.nombre like :nombre OR ';
				} 
				if ( $filtro['dataKey']=='icono' ) {
					$filtros .= ' Modulo2.icono like :icono OR ';
				} 
				if ( $filtro['dataKey']=='nombre_interno' ) {
					$filtros .= ' Modulo2.nombre_interno like :nombre_interno OR ';
				} 
				if ( $filtro['dataKey']=='ruta_base' ) {
					$filtros .= ' Modulo2.ruta_base like :ruta_base OR ';
				} 
				if ( $filtro['dataKey']=='orden' ) {
					$filtros .= ' Modulo2.orden like :orden OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' Modulo2 '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre' ) {
				$sth->bindValue(':nombre','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='icono' ) {
				$sth->bindValue(':icono','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_interno' ) {
				$sth->bindValue(':nombre_interno','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ruta_base' ) {
				$sth->bindValue(':ruta_base','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='orden' ) {
				$sth->bindValue(':orden','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}		
			}
		}
		$exito = $sth->execute();		
		if ( !$exito ){
			$error = $this->getError( $sth );
			throw new Exception($error['msg']); //TODO: agregar numero de error, crear una exception MiEscepcion
		}
		
		$tot = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = $tot[0]['total'];
		
		$paginar=false;
		if ( isset($params['limit']) && isset($params['start']) ){
			$paginar=true;
		}
		
		if ($paginar){
			$limit=$params['limit'];
			$start=$params['start'];
			$sql = 'SELECT Modulo2.id, Modulo2.nombre, Modulo2.icono, Modulo2.nombre_interno, Modulo2.ruta_base, Modulo2.orden FROM '.$this->tabla.' Modulo2 '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT Modulo2.id, Modulo2.nombre, Modulo2.icono, Modulo2.nombre_interno, Modulo2.ruta_base, Modulo2.orden FROM '.$this->tabla.' Modulo2 '.$joins.$filtros;
		}
				
		$sth = $pdo->prepare($sql);
		if ($paginar){
			$sth->bindValue(':limit',$limit,PDO::PARAM_INT);
			$sth->bindValue(':start',$start,PDO::PARAM_INT);
		}
		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre' ) {
				$sth->bindValue(':nombre','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='icono' ) {
				$sth->bindValue(':icono','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_interno' ) {
				$sth->bindValue(':nombre_interno','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ruta_base' ) {
				$sth->bindValue(':ruta_base','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='orden' ) {
				$sth->bindValue(':orden','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}	
			}
		}
		$exito = $sth->execute();

		
		if ( !$exito ){		
			$error = $this->getError( $sth );
			throw new Exception($error['msg']); //TODO: agregar numero de error, crear una exception MiEscepcion			
		}
		
		$modelos = $sth->fetchAll(PDO::FETCH_ASSOC);				
		
		return array(
			'success'=>true,
			'total'=>$total,
			'datos'=>$modelos
		);
	}
	
	function nuevo( $params ){
		$obj=array();
		
			$obj['id']='';
			$obj['nombre']='';
			$obj['icono']='';
			$obj['nombre_interno']='';
			$obj['ruta_base']='';
			$obj['orden']='';
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT Modulo2.id, Modulo2.nombre, Modulo2.icono, Modulo2.nombre_interno, Modulo2.ruta_base, Modulo2.orden
 FROM system_modulos AS Modulo2
  WHERE Modulo2.id=:id';
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		 $sth->BindValue(':id',$llave ); 
		$exito = $sth->execute();
		if ( !$exito ){
			$error =  $this->getError( $sth );
			throw new Exception($error['msg']);
		}
		
		$modelos = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		if ( empty($modelos) ){						
			throw new Exception("Elemento no encontrado");
		}
		
		if ( sizeof($modelos) > 1 ){			
			throw new Exception("El identificador está duplicado"); //TODO: agregar numero de error, crear una exception MiEscepcion
		}
		
		return $modelos[0];			
	}
	
	function guardar( $datos ){
	
		$esNuevo=( empty( $datos['id'] ) )? true : false;			
		$strCampos='';
		
		//--------------------------------------------
		// CAMPOS A GUARDAR
		 
		if ( isset( $datos['nombre'] ) ){
			$strCampos .= ' nombre=:nombre, ';
		} 
		if ( isset( $datos['icono'] ) ){
			$strCampos .= ' icono=:icono, ';
		} 
		if ( isset( $datos['nombre_interno'] ) ){
			$strCampos .= ' nombre_interno=:nombre_interno, ';
		} 
		if ( isset( $datos['ruta_base'] ) ){
			$strCampos .= ' ruta_base=:ruta_base, ';
		} 
		if ( isset( $datos['orden'] ) ){
			$strCampos .= ' orden=:orden, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Modulo Creado';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Modulo Actualizado';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['nombre'] ) ){
			$sth->bindValue(':nombre', $datos['nombre'] );
		}
		if  ( isset( $datos['icono'] ) ){
			$sth->bindValue(':icono', $datos['icono'] );
		}
		if  ( isset( $datos['nombre_interno'] ) ){
			$sth->bindValue(':nombre_interno', $datos['nombre_interno'] );
		}
		if  ( isset( $datos['ruta_base'] ) ){
			$sth->bindValue(':ruta_base', $datos['ruta_base'] );
		}
		if  ( isset( $datos['orden'] ) ){
			$sth->bindValue(':orden', $datos['orden'] );
		}		
		if ( !$esNuevo)	{
			$sth->bindValue(':id', $datos['id'] );
		}	
		//--------------------------------------------
		$exito = $sth->execute();
		if ( !$exito ){
			$error =  $this->getError( $sth );
			throw new Exception($error['msg']);
		}
		
		if ( $esNuevo ){
			$idObj=$pdo->lastInsertId();
		}else{
			$idObj=$datos['id'];
		}	
		
		
		
		
		$obj=$this->obtener( $idObj );
		return array(
			'success'=>true,
			'datos'=>$obj,
			'msg'=>$msg
		);
		
	}
}
?>