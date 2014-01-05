<?php
class conexionModelo extends Modelo{	
	var $tabla='constructor_db_config';
	var $pk='id';
	var $campos= array('id', 'host', 'db_name', 'user', 'pass', 'fk_app');
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' conexion.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='host' ) {
					$filtros .= ' conexion.host like :host OR ';
				} 
				if ( $filtro['dataKey']=='db_name' ) {
					$filtros .= ' conexion.db_name like :db_name OR ';
				} 
				if ( $filtro['dataKey']=='user' ) {
					$filtros .= ' conexion.user like :user OR ';
				} 
				if ( $filtro['dataKey']=='pass' ) {
					$filtros .= ' conexion.pass like :pass OR ';
				} 
				if ( $filtro['dataKey']=='fk_app' ) {
					$filtros .= ' conexion.fk_app like :fk_app OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' conexion '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='host' ) {
				$sth->bindValue(':host','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='db_name' ) {
				$sth->bindValue(':db_name','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='user' ) {
				$sth->bindValue(':user','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='pass' ) {
				$sth->bindValue(':pass','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_app' ) {
				$sth->bindValue(':fk_app','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$sql = 'SELECT conexion.id, conexion.host, conexion.db_name, conexion.user, conexion.pass, conexion.fk_app FROM '.$this->tabla.' conexion '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT conexion.id, conexion.host, conexion.db_name, conexion.user, conexion.pass, conexion.fk_app FROM '.$this->tabla.' conexion '.$joins.$filtros;
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
			if ( $filtro['dataKey']=='host' ) {
				$sth->bindValue(':host','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='db_name' ) {
				$sth->bindValue(':db_name','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='user' ) {
				$sth->bindValue(':user','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='pass' ) {
				$sth->bindValue(':pass','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_app' ) {
				$sth->bindValue(':fk_app','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$obj['host']='';
			$obj['db_name']='';
			$obj['user']='';
			$obj['pass']='';
			$obj['fk_app']='';
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT conexion.id, conexion.host, conexion.db_name, conexion.user, conexion.pass, conexion.fk_app
 FROM constructor_db_config AS conexion
  WHERE conexion.id=:id';
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
		 
		if ( isset( $datos['host'] ) ){
			$strCampos .= ' host=:host, ';
		} 
		if ( isset( $datos['db_name'] ) ){
			$strCampos .= ' db_name=:db_name, ';
		} 
		if ( isset( $datos['user'] ) ){
			$strCampos .= ' user=:user, ';
		} 
		if ( isset( $datos['pass'] ) ){
			$strCampos .= ' pass=:pass, ';
		} 
		if ( isset( $datos['fk_app'] ) ){
			$strCampos .= ' fk_app=:fk_app, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Conexion Creada';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Conexion Actualizada';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['host'] ) ){
			$sth->bindValue(':host', $datos['host'] );
		}
		if  ( isset( $datos['db_name'] ) ){
			$sth->bindValue(':db_name', $datos['db_name'] );
		}
		if  ( isset( $datos['user'] ) ){
			$sth->bindValue(':user', $datos['user'] );
		}
		if  ( isset( $datos['pass'] ) ){
			$sth->bindValue(':pass', $datos['pass'] );
		}
		if  ( isset( $datos['fk_app'] ) ){
			$sth->bindValue(':fk_app', $datos['fk_app'] );
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