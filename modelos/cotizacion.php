<?php
class cotizacionModelo extends Modelo{	
	var $tabla='exp_cotizacion';
	var $pk='id';
	var $campos= array('id', 'serie', 'folio', 'notas', 'fecha');
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' cotizacion.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='serie' ) {
					$filtros .= ' cotizacion.serie like :serie OR ';
				} 
				if ( $filtro['dataKey']=='folio' ) {
					$filtros .= ' cotizacion.folio like :folio OR ';
				} 
				if ( $filtro['dataKey']=='notas' ) {
					$filtros .= ' cotizacion.notas like :notas OR ';
				} 
				if ( $filtro['dataKey']=='fecha' ) {
					$filtros .= ' cotizacion.fecha like :fecha OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' cotizacion '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='serie' ) {
				$sth->bindValue(':serie','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='folio' ) {
				$sth->bindValue(':folio','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='notas' ) {
				$sth->bindValue(':notas','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fecha' ) {
				$sth->bindValue(':fecha','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$sql = 'SELECT cotizacion.id, cotizacion.serie, cotizacion.folio, cotizacion.notas, cotizacion.fecha FROM '.$this->tabla.' cotizacion '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT cotizacion.id, cotizacion.serie, cotizacion.folio, cotizacion.notas, cotizacion.fecha FROM '.$this->tabla.' cotizacion '.$joins.$filtros;
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
			if ( $filtro['dataKey']=='serie' ) {
				$sth->bindValue(':serie','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='folio' ) {
				$sth->bindValue(':folio','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='notas' ) {
				$sth->bindValue(':notas','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fecha' ) {
				$sth->bindValue(':fecha','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$obj['serie']='';
			$obj['folio']='';
			$obj['notas']='';
			$obj['fecha']='';
			$obj['conceptosDeCotizacion']=array();
			
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT cotizacion.id, cotizacion.serie, cotizacion.folio, cotizacion.notas, cotizacion.fecha
 FROM exp_cotizacion AS cotizacion
  WHERE cotizacion.id=:id';
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
		
				//----------------------------
				$conceptosMod=new concepto_cotizacionModelo();
				$params=array(
					'filtros'=>array(
						array(
							'filterValue'=>$modelos[0]['id'],
							'filterOperator'=>'equals',
							'dataKey'=>'fk_cotizacion'
						)
					)
				);
				$conceptosDeCotizacion=$conceptosMod->buscar($params);				
				$modelos[0]['conceptosDeCotizacion'] =$conceptosDeCotizacion['datos'];
				//---------------------------
				
		return $modelos[0];			
	}
	
	function guardar( $datos ){
	
		$esNuevo=( empty( $datos['id'] ) )? true : false;			
		$strCampos='';
		
		//--------------------------------------------
		// CAMPOS A GUARDAR
		 
		if ( isset( $datos['serie'] ) ){
			$strCampos .= ' serie=:serie, ';
		} 
		if ( isset( $datos['folio'] ) ){
			$strCampos .= ' folio=:folio, ';
		} 
		if ( isset( $datos['notas'] ) ){
			$strCampos .= ' notas=:notas, ';
		} 
		if ( isset( $datos['fecha'] ) ){
			$strCampos .= ' fecha=:fecha, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Cotizacion Creada';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Cotizacion Actualizada';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['serie'] ) ){
			$sth->bindValue(':serie', $datos['serie'] );
		}
		if  ( isset( $datos['folio'] ) ){
			$sth->bindValue(':folio', $datos['folio'] );
		}
		if  ( isset( $datos['notas'] ) ){
			$sth->bindValue(':notas', $datos['notas'] );
		}
		if  ( isset( $datos['fecha'] ) ){
			$sth->bindValue(':fecha', $datos['fecha'] );
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
		
		
		
		
		$concepto_cotizacionMod = new concepto_cotizacionModelo();
		foreach( $datos['conceptosDeCotizacion'] as $el ){
			$el['fk_cotizacion']=$idObj;
			$res=$concepto_cotizacionMod->guardar($el);
			if ( !$res['success'] ){											
				return $res;
			}
			
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