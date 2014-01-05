<?php
class PlantillaModelo extends Modelo{	
	//var $tabla;
	//var $pk;
	//MODELO-CAMPOS
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				//buscar()-FILTROS			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='{buscar()-JOINS}';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' {buscar()-nombreModelo} '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				//buscar()-BIND_FILTROS		
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
			$sql = 'SELECT {CAMPOS-SELECT} FROM '.$this->tabla.' {NOMBRE-MODELO} '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT {CAMPOS-SELECT} FROM '.$this->tabla.' {NOMBRE-MODELO} '.$joins.$filtros;
		}
				
		$sth = $pdo->prepare($sql);
		if ($paginar){
			$sth->bindValue(':limit',$limit,PDO::PARAM_INT);
			$sth->bindValue(':start',$start,PDO::PARAM_INT);
		}
		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				//buscar()-BIND_FILTROS	
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
		//nuevo()-ATTRIBUTOS
		return $obj;
	}
	function obtener( $llave ){		
		$sql = '{SQL-obtener}';
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//{FILTRO-OBTENER}
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
		// {OBTENER-ELEMENTOS-TABLA}
		return $modelos[0];			
	}
	
	function guardar( $datos ){
	
		$esNuevo=( empty( $datos['{CAMPOLLAVE}'] ) )? true : false;			
		$strCampos='';
		
		//--------------------------------------------
		// CAMPOS A GUARDAR
		//{guardar()-codigoCampos}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='{guardar()-MSG_NUEVO}';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE {CAMPOLLAVE}=:{CAMPOLLAVE}';
			$msg='{guardar()-MSG_ACTUALIZADO}';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		//{guardar()-codigoBindCampos}		
		if ( !$esNuevo)	{
			$sth->bindValue(':{CAMPOLLAVE}', $datos['{CAMPOLLAVE}'] );
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
			$idObj=$datos['{CAMPOLLAVE}'];
		}	
		
		
		
		//{TABLAS}
		$obj=$this->obtener( $idObj );
		return array(
			'success'=>true,
			'datos'=>$obj,
			'msg'=>$msg
		);
		
	}
}
?>