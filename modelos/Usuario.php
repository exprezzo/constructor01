<?php
class UsuarioModelo extends Modelo{	
	var $tabla='system_usuarios';
	var $pk='id';
	var $campos= array('id', 'username', 'pass', 'email', 'nombre', 'ultima_conexion', 'creado', 'fk_rol', 'ip');
	function identificar($usuario, $contra){
				
		$sql = 'SELECT * FROM '.$this->tabla.' WHERE username=:usuario and pass=:pass';				
		$con = $this->getConexion();
		$sth = $con->prepare($sql);		
		$sth->bindValue(':usuario',$usuario);		
		$sth->bindValue(':pass',md5($contra) );		
		$exito = $sth->execute();
		$modelos = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		if ( !$exito ){
			$error = $this->getError($sth);			
			return $error;
		}
		if ( empty($modelos) ){
			//throw new Exception(); //TODO: agregar numero de error, crear una exception MiEscepcion			
			return array('success'=>false,'error'=>'Usuario o contraseña incorrecta','msg'=>'Usuario o contraseña incorrecta');
		}
		
		if ( sizeof($modelos) > 1 ){
			throw new Exception("El identificador está duplicado"); //TODO: agregar numero de error, crear una exception MiEscepcion
		}
		unset ($modelos[0]['pass']);
		
		
		
		$date = new DateTime();
		$user=array(
			'id'			 =>$modelos[0]['id'],
			'ultima_conexion'=>$date->format('Y-m-d H:i:s')			
		);
		
		$res = $this->guardar( $user );
		
		
		return array(
			'success'=>true,
			'usuario'=>$modelos[0]
		);
	}
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' Usuario.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='username' ) {
					$filtros .= ' Usuario.username like :username OR ';
				} 
				if ( $filtro['dataKey']=='pass' ) {
					$filtros .= ' Usuario.pass like :pass OR ';
				} 
				if ( $filtro['dataKey']=='email' ) {
					$filtros .= ' Usuario.email like :email OR ';
				} 
				if ( $filtro['dataKey']=='nombre' ) {
					$filtros .= ' Usuario.nombre like :nombre OR ';
				} 
				if ( $filtro['dataKey']=='ultima_conexion' ) {
					$filtros .= ' Usuario.ultima_conexion like :ultima_conexion OR ';
				} 
				if ( $filtro['dataKey']=='creado' ) {
					$filtros .= ' Usuario.creado like :creado OR ';
				} 
				if ( $filtro['dataKey']=='fk_rol' ) {
					$filtros .= ' Usuario.fk_rol like :fk_rol OR ';
				} 
				if ( $filtro['dataKey']=='ip' ) {
					$filtros .= ' Usuario.ip like :ip OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' Usuario '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='username' ) {
				$sth->bindValue(':username','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='pass' ) {
				$sth->bindValue(':pass','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='email' ) {
				$sth->bindValue(':email','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre' ) {
				$sth->bindValue(':nombre','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ultima_conexion' ) {
				$sth->bindValue(':ultima_conexion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='creado' ) {
				$sth->bindValue(':creado','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_rol' ) {
				$sth->bindValue(':fk_rol','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ip' ) {
				$sth->bindValue(':ip','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$sql = 'SELECT Usuario.id, Usuario.username, Usuario.email, Usuario.nombre, Usuario.ultima_conexion, Usuario.creado, Usuario.fk_rol, Usuario.ip FROM '.$this->tabla.' Usuario '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT Usuario.id, Usuario.username, Usuario.email, Usuario.nombre, Usuario.ultima_conexion, Usuario.creado, Usuario.fk_rol, Usuario.ip FROM '.$this->tabla.' Usuario '.$joins.$filtros;
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
			if ( $filtro['dataKey']=='username' ) {
				$sth->bindValue(':username','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='pass' ) {
				$sth->bindValue(':pass','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='email' ) {
				$sth->bindValue(':email','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nombre' ) {
				$sth->bindValue(':nombre','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ultima_conexion' ) {
				$sth->bindValue(':ultima_conexion','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='creado' ) {
				$sth->bindValue(':creado','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fk_rol' ) {
				$sth->bindValue(':fk_rol','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='ip' ) {
				$sth->bindValue(':ip','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$obj['username']='';
			$obj['pass']='';
			$obj['email']='';
			$obj['nombre']='';
			$obj['ultima_conexion']='';
			$obj['creado']='';
			$obj['fk_rol']='';
			$obj['ip']='';
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT Usuario.id, Usuario.username, Usuario.pass, Usuario.email, Usuario.nombre, Usuario.ultima_conexion, Usuario.creado, Usuario.fk_rol, Usuario.ip
 FROM system_usuarios AS Usuario
  WHERE Usuario.id=:id';
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
		
		if ( !empty($datos['pass']) ){
			$datos['pass']=md5( $datos['pass'] );
		} 
		if ( empty($datos['pass']) && empty($datos['id']) ){
			return array(
				'success'=>false,
				'msg'=>utf8_encode('Escriba una contraseña')
			);
		}
		
		if ( !empty($datos['id']) && empty($datos['pass'])){
			unset($datos['pass']);
		}
		//--------------------------------------------
		// CAMPOS A GUARDAR
		 
		if ( isset( $datos['username'] ) ){
			$strCampos .= ' username=:username, ';
		} 
		if ( isset( $datos['pass'] ) ){
			$strCampos .= ' pass=:pass, ';
		} 
		if ( isset( $datos['email'] ) ){
			$strCampos .= ' email=:email, ';
		} 
		if ( isset( $datos['nombre'] ) ){
			$strCampos .= ' nombre=:nombre, ';
		} 
		if ( isset( $datos['ultima_conexion'] ) ){
			$strCampos .= ' ultima_conexion=:ultima_conexion, ';
		} 
		if ( isset( $datos['creado'] ) ){
			$strCampos .= ' creado=:creado, ';
		} 
		if ( isset( $datos['fk_rol'] ) ){
			$strCampos .= ' fk_rol=:fk_rol, ';
		} 
		if ( isset( $datos['ip'] ) ){
			$strCampos .= ' ip=:ip, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Usuario Creado';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Usuario Actualizado';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['username'] ) ){
			$sth->bindValue(':username', $datos['username'] );
		}
		if  ( isset( $datos['pass'] ) ){
			$sth->bindValue(':pass', $datos['pass'] );
		}
		if  ( isset( $datos['email'] ) ){
			$sth->bindValue(':email', $datos['email'] );
		}
		if  ( isset( $datos['nombre'] ) ){
			$sth->bindValue(':nombre', $datos['nombre'] );
		}
		if  ( isset( $datos['ultima_conexion'] ) ){
			$sth->bindValue(':ultima_conexion', $datos['ultima_conexion'] );
		}
		if  ( isset( $datos['creado'] ) ){
			$sth->bindValue(':creado', $datos['creado'] );
		}
		if  ( isset( $datos['fk_rol'] ) ){
			$sth->bindValue(':fk_rol', $datos['fk_rol'] );
		}
		if  ( isset( $datos['ip'] ) ){
			$sth->bindValue(':ip', $datos['ip'] );
		}		
		if ( !$esNuevo)	{
			$sth->bindValue(':id', $datos['id'] );
		}	
		//--------------------------------------------
		$exito = $sth->execute();
		if ( !$exito ){
			
			$error=$sth->errorInfo();						
			$msg=$error[2];						
			$datos=array();
			$errCode=$error[1];
			
			if ( $errCode==1062 ){
				$aparece = strstr ($msg, 'for key \'email\'');
				if ( strlen($aparece)>0 ){
					$msg = utf8_encode('Ese email ya existe, escoja otro');
				}
				
				$aparece = strstr ($msg, 'for key \'nick\'');
				if ( strlen($aparece)>0 ){
					$msg = utf8_encode('Ese usuario ya existe, escoja otro');
				}				
				
			}
			return array(
				'success'=>false,
				'msg'=>$msg
			);
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