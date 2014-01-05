<?php
class paginaModelo extends Modelo{	
	var $tabla='system_pagina';
	var $pk='id';
	var $campos= array('id', 'titulo', 'autor', 'name_autor', 'contenido', 'fk_categoria_pagina', 'nombre_categoria', 'fecha_creacion', 'ultima_edicion');
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' pagina.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='titulo' ) {
					$filtros .= ' pagina.titulo like :titulo OR ';
				} 
				if ( $filtro['dataKey']=='autor' ) {
					$filtros .= ' pagina.autor like :autor OR ';
				} 
				if ( $filtro['dataKey']=='name_autor' ) {
					$filtros .= ' autor0.name like :name_autor OR ';
				} 
				if ( $filtro['dataKey']=='contenido' ) {
					$filtros .= ' pagina.contenido like :contenido OR ';
				} 
				if ( $filtro['dataKey']=='fk_categoria_pagina' ) {
					$filtros .= ' pagina.fk_categoria_pagina like :fk_categoria_pagina OR ';
				} 
				if ( $filtro['dataKey']=='nombre_categoria' ) {
					$filtros .= ' categoria1.nombre like :nombre_categoria OR ';
				} 
				if ( $filtro['dataKey']=='fecha_creacion' ) {
					$filtros .= ' pagina.fecha_creacion like :fecha_creacion OR ';
				} 
				if ( $filtro['dataKey']=='ultima_edicion' ) {
					$filtros .= ' pagina.ultima_edicion like :ultima_edicion OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='
 LEFT JOIN system_users AS autor0 ON autor0.id = pagina.autor
 LEFT JOIN cms_categoria AS categoria1 ON categoria1.id = pagina.fk_categoria_pagina';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' pagina '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='titulo' ) {
				$sth->bindValue(':titulo','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='autor' ) {
				$sth->bindValue(':autor','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='name_autor' ) {
				$sth->bindValue(':name_autor', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='contenido' ) {
				$sth->bindValue(':contenido','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_categoria_pagina' ) {
				$sth->bindValue(':fk_categoria_pagina','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_categoria' ) {
				$sth->bindValue(':nombre_categoria', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fecha_creacion' ) {
				$sth->bindValue(':fecha_creacion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ultima_edicion' ) {
				$sth->bindValue(':ultima_edicion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$sql = 'SELECT pagina.id, pagina.titulo, pagina.autor, autor0.name AS name_autor, pagina.contenido, pagina.fk_categoria_pagina, categoria1.nombre AS nombre_categoria, pagina.fecha_creacion, pagina.ultima_edicion FROM '.$this->tabla.' pagina '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT pagina.id, pagina.titulo, pagina.autor, autor0.name AS name_autor, pagina.contenido, pagina.fk_categoria_pagina, categoria1.nombre AS nombre_categoria, pagina.fecha_creacion, pagina.ultima_edicion FROM '.$this->tabla.' pagina '.$joins.$filtros;
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
			if ( $filtro['dataKey']=='titulo' ) {
				$sth->bindValue(':titulo','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='autor' ) {
				$sth->bindValue(':autor','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='name_autor' ) {
				$sth->bindValue(':name_autor', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='contenido' ) {
				$sth->bindValue(':contenido','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_categoria_pagina' ) {
				$sth->bindValue(':fk_categoria_pagina','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre_categoria' ) {
				$sth->bindValue(':nombre_categoria', '%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fecha_creacion' ) {
				$sth->bindValue(':fecha_creacion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ultima_edicion' ) {
				$sth->bindValue(':ultima_edicion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$obj['titulo']='';
			$obj['autor']='';
			$obj['name_autor']='';
			$obj['contenido']='';
			$obj['fk_categoria_pagina']='';
			$obj['nombre_categoria']='';
			$obj['fecha_creacion']='';
			$obj['ultima_edicion']='';
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT pagina.id, pagina.titulo, pagina.autor, autor0.name AS name_autor, pagina.contenido, pagina.fk_categoria_pagina, categoria1.nombre AS nombre_categoria, pagina.fecha_creacion, pagina.ultima_edicion
 FROM system_pagina AS pagina
 LEFT JOIN system_users AS autor0 ON autor0.id = pagina.autor
 LEFT JOIN cms_categoria AS categoria1 ON categoria1.id = pagina.fk_categoria_pagina
  WHERE pagina.id=:id';
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
		 
		if ( isset( $datos['titulo'] ) ){
			$strCampos .= ' titulo=:titulo, ';
		} 
		if ( isset( $datos['autor'] ) ){
			$strCampos .= ' autor=:autor, ';
		} 
		if ( isset( $datos['contenido'] ) ){
			$strCampos .= ' contenido=:contenido, ';
		} 
		if ( isset( $datos['fk_categoria_pagina'] ) ){
			$strCampos .= ' fk_categoria_pagina=:fk_categoria_pagina, ';
		} 
		if ( isset( $datos['fecha_creacion'] ) ){
			$strCampos .= ' fecha_creacion=:fecha_creacion, ';
		} 
		if ( isset( $datos['ultima_edicion'] ) ){
			$strCampos .= ' ultima_edicion=:ultima_edicion, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Pagina Creada';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Pagina Actualizada';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['titulo'] ) ){
			$sth->bindValue(':titulo', $datos['titulo'] );
		}
		if  ( isset( $datos['autor'] ) ){
			$sth->bindValue(':autor', $datos['autor'] );
		}
		if  ( isset( $datos['contenido'] ) ){
			$sth->bindValue(':contenido', $datos['contenido'] );
		}
		if  ( isset( $datos['fk_categoria_pagina'] ) ){
			$sth->bindValue(':fk_categoria_pagina', $datos['fk_categoria_pagina'] );
		}
		if  ( isset( $datos['fecha_creacion'] ) ){
			$sth->bindValue(':fecha_creacion', $datos['fecha_creacion'] );
		}
		if  ( isset( $datos['ultima_edicion'] ) ){
			$sth->bindValue(':ultima_edicion', $datos['ultima_edicion'] );
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