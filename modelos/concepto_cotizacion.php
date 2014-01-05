<?php
class concepto_cotizacionModelo extends Modelo{	
	var $tabla='exp_conceptos_cotizacion';
	var $pk='id';
	var $campos= array('id', 'fk_concepto', 'nombre_concepto', 'cantidad', 'fk_um', 'nombre_unidad', 'precio_unitario', 'importe', 'fk_cotizacion', 'folio_cotizacion');
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' concepto_cotizacion.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='fk_concepto' ) {
					$filtros .= ' concepto_cotizacion.fk_concepto like :fk_concepto OR ';
				} 
				if ( $filtro['dataKey']=='nombre_concepto' ) {
					$filtros .= ' concepto0.nombre like :nombre_concepto OR ';
				} 
				if ( $filtro['dataKey']=='cantidad' ) {
					$filtros .= ' concepto_cotizacion.cantidad like :cantidad OR ';
				} 
				if ( $filtro['dataKey']=='fk_um' ) {
					$filtros .= ' concepto_cotizacion.fk_um like :fk_um OR ';
				} 
				if ( $filtro['dataKey']=='nombre_unidad' ) {
					$filtros .= ' unidad1.nombre like :nombre_unidad OR ';
				} 
				if ( $filtro['dataKey']=='precio_unitario' ) {
					$filtros .= ' concepto_cotizacion.precio_unitario like :precio_unitario OR ';
				} 
				if ( $filtro['dataKey']=='importe' ) {
					$filtros .= ' concepto_cotizacion.importe like :importe OR ';
				} 
				if ( $filtro['dataKey']=='fk_cotizacion' ) {
					$filtros .= ' concepto_cotizacion.fk_cotizacion like :fk_cotizacion OR ';
				} 
				if ( $filtro['dataKey']=='folio_cotizacion' ) {
					$filtros .= ' cotizacion2.folio like :folio_cotizacion OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='
 LEFT JOIN exp_concepto AS concepto0 ON concepto0.id = concepto_cotizacion.fk_concepto
 LEFT JOIN exp_um AS unidad1 ON unidad1.id = concepto_cotizacion.fk_um
 LEFT JOIN exp_cotizacion AS cotizacion2 ON cotizacion2.id = concepto_cotizacion.fk_cotizacion';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' concepto_cotizacion '.$joins.$filtros;
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_concepto' ) {
				$sth->bindValue(':fk_concepto','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_concepto' ) {
				$sth->bindValue(':nombre_concepto', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='cantidad' ) {
				$sth->bindValue(':cantidad','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_um' ) {
				$sth->bindValue(':fk_um','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_unidad' ) {
				$sth->bindValue(':nombre_unidad', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='precio_unitario' ) {
				$sth->bindValue(':precio_unitario','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='importe' ) {
				$sth->bindValue(':importe','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_cotizacion' ) {
				$sth->bindValue(':fk_cotizacion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='folio_cotizacion' ) {
				$sth->bindValue(':folio_cotizacion', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$sql = 'SELECT concepto_cotizacion.id, concepto_cotizacion.fk_concepto, concepto0.nombre AS nombre_concepto, concepto_cotizacion.cantidad, concepto_cotizacion.fk_um, unidad1.nombre AS nombre_unidad, concepto_cotizacion.precio_unitario, concepto_cotizacion.importe, concepto_cotizacion.fk_cotizacion, cotizacion2.folio AS folio_cotizacion FROM '.$this->tabla.' concepto_cotizacion '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT concepto_cotizacion.id, concepto_cotizacion.fk_concepto, concepto0.nombre AS nombre_concepto, concepto_cotizacion.cantidad, concepto_cotizacion.fk_um, unidad1.nombre AS nombre_unidad, concepto_cotizacion.precio_unitario, concepto_cotizacion.importe, concepto_cotizacion.fk_cotizacion, cotizacion2.folio AS folio_cotizacion FROM '.$this->tabla.' concepto_cotizacion '.$joins.$filtros;
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
			if ( $filtro['dataKey']=='fk_concepto' ) {
				$sth->bindValue(':fk_concepto','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_concepto' ) {
				$sth->bindValue(':nombre_concepto', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='cantidad' ) {
				$sth->bindValue(':cantidad','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_um' ) {
				$sth->bindValue(':fk_um','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_unidad' ) {
				$sth->bindValue(':nombre_unidad', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='precio_unitario' ) {
				$sth->bindValue(':precio_unitario','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='importe' ) {
				$sth->bindValue(':importe','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_cotizacion' ) {
				$sth->bindValue(':fk_cotizacion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='folio_cotizacion' ) {
				$sth->bindValue(':folio_cotizacion', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
		$obj['fk_concepto']='';
		$obj['nombre_concepto']='';
		$obj['cantidad']='';
		$obj['fk_um']='';
		$obj['nombre_unidad']='';
		$obj['precio_unitario']='';
		$obj['importe']='';
		$obj['fk_cotizacion']='';
		$obj['folio_cotizacion']='';
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT concepto_cotizacion.id, concepto_cotizacion.fk_concepto, concepto0.nombre AS nombre_concepto, concepto_cotizacion.cantidad, concepto_cotizacion.fk_um, unidad1.nombre AS nombre_unidad, concepto_cotizacion.precio_unitario, concepto_cotizacion.importe, concepto_cotizacion.fk_cotizacion, cotizacion2.folio AS folio_cotizacion
 FROM exp_conceptos_cotizacion AS concepto_cotizacion
 LEFT JOIN exp_concepto AS concepto0 ON concepto0.id = concepto_cotizacion.fk_concepto
 LEFT JOIN exp_um AS unidad1 ON unidad1.id = concepto_cotizacion.fk_um
 LEFT JOIN exp_cotizacion AS cotizacion2 ON cotizacion2.id = concepto_cotizacion.fk_cotizacion
  WHERE concepto_cotizacion.id=:id';
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
		 
		if ( isset( $datos['fk_concepto'] ) ){
			$strCampos .= ' fk_concepto=:fk_concepto, ';
		} 
		if ( isset( $datos['cantidad'] ) ){
			$strCampos .= ' cantidad=:cantidad, ';
		} 
		if ( isset( $datos['fk_um'] ) ){
			$strCampos .= ' fk_um=:fk_um, ';
		} 
		if ( isset( $datos['precio_unitario'] ) ){
			$strCampos .= ' precio_unitario=:precio_unitario, ';
		} 
		if ( isset( $datos['importe'] ) ){
			$strCampos .= ' importe=:importe, ';
		} 
		if ( isset( $datos['fk_cotizacion'] ) ){
			$strCampos .= ' fk_cotizacion=:fk_cotizacion, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Concepto Creado';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Concepto Actualizado';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['fk_concepto'] ) ){
			$sth->bindValue(':fk_concepto', $datos['fk_concepto'] );
		}
		if  ( isset( $datos['cantidad'] ) ){
			$sth->bindValue(':cantidad', $datos['cantidad'] );
		}
		if  ( isset( $datos['fk_um'] ) ){
			$sth->bindValue(':fk_um', $datos['fk_um'] );
		}
		if  ( isset( $datos['precio_unitario'] ) ){
			$sth->bindValue(':precio_unitario', $datos['precio_unitario'] );
		}
		if  ( isset( $datos['importe'] ) ){
			$sth->bindValue(':importe', $datos['importe'] );
		}
		if  ( isset( $datos['fk_cotizacion'] ) ){
			$sth->bindValue(':fk_cotizacion', $datos['fk_cotizacion'] );
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