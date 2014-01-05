<?php
class CatalogoModelo extends Modelo{
	var $tabla="system_catalogos";
	var $campos=array('id','fk_modulo','nombre','controlador','modelo','tabla','pk_tabla','icono','titulo_nuevo','titulo_edicion','titulo_busqueda','msg_creado','msg_actualizado','pregunta_eliminar','msg_eliminado','msg_cambios','campos_busqueda');
	var $pk="id";
	var $nombre='Catalogo';

	function obtener($params){
		$res=parent::obtener($params);
		$fk_catalogo=$params['id'];
		
		
		$res['elementos']= $this->getElementos($fk_catalogo);
		return $res;
	}
	
	function nuevo($params){
		return parent::nuevo($params);
	}
	
	function guardarElementos($elementos, $fk_catalogo){
		
		$mod = new elementoModelo();			
		foreach($elementos as $elemento){
			
			$elemento['fk_catalogo'] = $fk_catalogo;				 			
			unset( $elemento['dataItemIndex'] );
			unset( $elemento['sectionRowIndex'] );
			 if ( !empty($elemento['eliminado']) ){
				if ( !empty($elemento['id']) ){
					$res = $mod->eliminar($elemento);
					if ($res )$res =array('success'=>true);
				}else{
					$res=array('success'=>true);
				}					
			 }else{
				unset( $elemento['eliminado'] );
				 // if ( empty($elemento['descripcion'])  || empty($elemento['unidad']) )  continue;
				$res = $mod->guardar($elemento);
				// print_r($res);
			 }
			 
			 if ( !$res['success'] ){					
				return $res;
			}
		}
		return array('success'=>true);
	}
	
	function getElementos($fk_catalogo){			
		$mod = new elementoModelo();
		
		$filtros=array();
		$filtros[]=array(
			'dataKey'=>'fk_catalogo',
			'filterOperator'=>'equals',
			'filterValue'=>$fk_catalogo
		);
		$params=array('filtros'=>$filtros);
		$res = $mod->buscar( $params );		
		return $res['datos'];
	}
	function guardar( $params ){
		$dbh=$this->getConexion();		
		$dbh->beginTransaction();
		
		$elementos= empty( $params['elementos'] )? array() : $params['elementos'];
		unset( $params['elementos'] );
		$id=empty($params[$this->pk])? 0 : $params[$this->pk] ;
		
		$nuevo = false;
		if ( empty($id) ){ 	//-----------------------------------------CREAR
			$nuevo = true;  // bandera			
			$sql='INSERT INTO '.$this->tabla.' SET ';
			foreach($params as $key=>$val){
				$sql.="$key=:$key, ";
			}
			$sql=substr($sql, 0, strlen($sql)-2 );			
			$sth = $dbh->prepare($sql);							
			foreach($params as $key=>$val){
				$bind=":$key";
				$sth->bindValue($bind, $val,PDO::PARAM_STR);					
			}			
			$msg=$this->nombre.' Creado';	
		}else{ 	//-----------------------------------------ACTUALIZAR						
			$sql='UPDATE '.$this->tabla.' SET ';
			foreach($params as $key=>$val){
				if ($key==$this->pk ) continue;
				$sql.="$key=:$key, ";
			}
			$sql=substr($sql, 0, strlen($sql)-2 );
			$sql.=' WHERE '.$this->pk.'=:'.$this->pk;
			// nombre=:nombre';
			$sth = $dbh->prepare($sql);							
			foreach($params as $key=>$val){
				$bind=":$key";
				$sth->bindValue($bind, $val,PDO::PARAM_STR);					
			}
			
			$msg=$this->nombre.' Actualizado';	
		}
		
		$success = $sth->execute();
		
		$errCode = 0;
		if ($success !== true){
			$dbh->rollBack();
			$error=$sth->errorInfo();			
			
			
			$success=false; //plionasmo apropósito
			$msg=$error[2];						
			$datos=array();
			$errCode=$error[1];
			// echo $msg.$sql; exit;
		}else{
			
			if ( empty( $id) ){
				$id=$dbh->lastInsertId();
			}
			
		}
		
		//si es nuevo, y success, se agrega una relacion entre el catalogo y el elemento
		
		if ($success){
			
			$res = $this->guardarElementos($elementos, $id);
			
			$datos=$this->obtener(
				array( $this->pk =>$id )
			);
			if ($res['success']){
				$dbh->commit();
			}else{
				$dbh->rollBack();
				$success=false;
				$msg=$res['msg'];
			}
		}
		
		
		
		return array(
			'success'	=>$success,		
			'datos' 	=>$datos,
			'msg'		=>$msg,
			'errCode'	=>$errCode
		);	
				
	}
	function borrar($params){
		return parent::borrar($params);
	}
	function editar($params){
		return parent::obtener($params);
	}
	function buscar($params){
		return parent::buscar($params);
	}
}
?>