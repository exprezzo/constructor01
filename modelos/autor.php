<?php
class autorModelo extends Modelo{	
	var $tabla='system_users';
	var $pk='id';
	var $campos= array('id', 'nick', 'pass', 'email', 'rol', 'fbid', 'name', 'picture', 'originalName');
	
	function buscar($params){
		
		$pdo = $this->getConexion();
		$filtros='';
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				 
				if ( $filtro['dataKey']=='id' ) {
					$filtros .= ' autor.id like :id OR ';
				} 
				if ( $filtro['dataKey']=='nick' ) {
					$filtros .= ' autor.nick like :nick OR ';
				} 
				if ( $filtro['dataKey']=='pass' ) {
					$filtros .= ' autor.pass like :pass OR ';
				} 
				if ( $filtro['dataKey']=='email' ) {
					$filtros .= ' autor.email like :email OR ';
				} 
				if ( $filtro['dataKey']=='rol' ) {
					$filtros .= ' autor.rol like :rol OR ';
				} 
				if ( $filtro['dataKey']=='fbid' ) {
					$filtros .= ' autor.fbid like :fbid OR ';
				} 
				if ( $filtro['dataKey']=='name' ) {
					$filtros .= ' autor.name like :name OR ';
				} 
				if ( $filtro['dataKey']=='picture' ) {
					$filtros .= ' autor.picture like :picture OR ';
				} 
				if ( $filtro['dataKey']=='originalName' ) {
					$filtros .= ' autor.originalName like :originalName OR ';
				}			
			}
			$filtros=substr( $filtros,0,  strlen($filtros)-3 );
			if ( !empty($filtros) ){
				$filtros=' WHERE '.$filtros;
			}
		}
		
		
		$joins='';
						
		$sql = 'SELECT COUNT(*) as total FROM '.$this->tabla.' autor '.$joins.$filtros;				
		$sth = $pdo->prepare($sql);		
		if ( !empty($params['filtros']) ){
			foreach($params['filtros'] as $filtro){
				
			if ( $filtro['dataKey']=='id' ) {
				$sth->bindValue(':id','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='nick' ) {
				$sth->bindValue(':nick','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='pass' ) {
				$sth->bindValue(':pass','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='email' ) {
				$sth->bindValue(':email','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='rol' ) {
				$sth->bindValue(':rol','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fbid' ) {
				$sth->bindValue(':fbid','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='name' ) {
				$sth->bindValue(':name','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='picture' ) {
				$sth->bindValue(':picture','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='originalName' ) {
				$sth->bindValue(':originalName','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$sql = 'SELECT autor.id, autor.nick, autor.pass, autor.email, autor.rol, autor.fbid, autor.name, autor.picture, autor.originalName FROM '.$this->tabla.' autor '.$joins.$filtros.' limit :start,:limit';
		}else{
			$sql = 'SELECT autor.id, autor.nick, autor.pass, autor.email, autor.rol, autor.fbid, autor.name, autor.picture, autor.originalName FROM '.$this->tabla.' autor '.$joins.$filtros;
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
			if ( $filtro['dataKey']=='nick' ) {
				$sth->bindValue(':nick','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='pass' ) {
				$sth->bindValue(':pass','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='email' ) {
				$sth->bindValue(':email','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='rol' ) {
				$sth->bindValue(':rol','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='fbid' ) {
				$sth->bindValue(':fbid','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='name' ) {
				$sth->bindValue(':name','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='picture' ) {
				$sth->bindValue(':picture','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
			}
			if ( $filtro['dataKey']=='originalName' ) {
				$sth->bindValue(':originalName','%'.$filtro['filterValue'].'%', PDO::PARAM_STR );
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
			$obj['nick']='';
			$obj['pass']='';
			$obj['email']='';
			$obj['rol']='';
			$obj['fbid']='';
			$obj['name']='';
			$obj['picture']='';
			$obj['originalName']='';
			$obj['paginasDeAutor']=array();
			
		return $obj;
	}
	function obtener( $llave ){		
		$sql = 'SELECT autor.id, autor.nick, autor.pass, autor.email, autor.rol, autor.fbid, autor.name, autor.picture, autor.originalName
 FROM system_users AS autor
  WHERE autor.id=:id';
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
				$conceptosMod=new paginaModelo();
				$params=array(
					'filtros'=>array(
						array(
							'filterValue'=>$modelos[0]['id'],
							'filterOperator'=>'equals',
							'dataKey'=>'autor'
						)
					)
				);
				$paginasDeAutor=$conceptosMod->buscar($params);				
				$modelos[0]['paginasDeAutor'] =$paginasDeAutor['datos'];
				//---------------------------
				
		return $modelos[0];			
	}
	
	function guardar( $datos ){
	
		$esNuevo=( empty( $datos['id'] ) )? true : false;			
		$strCampos='';
		
		//--------------------------------------------
		// CAMPOS A GUARDAR
		 
		if ( isset( $datos['nick'] ) ){
			$strCampos .= ' nick=:nick, ';
		} 
		if ( isset( $datos['pass'] ) ){
			$strCampos .= ' pass=:pass, ';
		} 
		if ( isset( $datos['email'] ) ){
			$strCampos .= ' email=:email, ';
		} 
		if ( isset( $datos['rol'] ) ){
			$strCampos .= ' rol=:rol, ';
		} 
		if ( isset( $datos['fbid'] ) ){
			$strCampos .= ' fbid=:fbid, ';
		} 
		if ( isset( $datos['name'] ) ){
			$strCampos .= ' name=:name, ';
		} 
		if ( isset( $datos['picture'] ) ){
			$strCampos .= ' picture=:picture, ';
		} 
		if ( isset( $datos['originalName'] ) ){
			$strCampos .= ' originalName=:originalName, ';
		}		
		//--------------------------------------------
		
		$strCampos=substr( $strCampos,0,  strlen($strCampos)-2 );
		
		
		if ( $esNuevo ){
			$sql = 'INSERT INTO '.$this->tabla.' SET '.$strCampos;
			$msg='Autor Creado';
		}else{
			$sql = 'UPDATE '.$this->tabla.' SET '.$strCampos.' WHERE id=:id';
			$msg='Autor Actualizado';
		}
		
		$pdo = $this->getConexion();
		$sth = $pdo->prepare($sql);
		//--------------------------------------------		
		// BIND VALUES
		
		if  ( isset( $datos['nick'] ) ){
			$sth->bindValue(':nick', $datos['nick'] );
		}
		if  ( isset( $datos['pass'] ) ){
			$sth->bindValue(':pass', $datos['pass'] );
		}
		if  ( isset( $datos['email'] ) ){
			$sth->bindValue(':email', $datos['email'] );
		}
		if  ( isset( $datos['rol'] ) ){
			$sth->bindValue(':rol', $datos['rol'] );
		}
		if  ( isset( $datos['fbid'] ) ){
			$sth->bindValue(':fbid', $datos['fbid'] );
		}
		if  ( isset( $datos['name'] ) ){
			$sth->bindValue(':name', $datos['name'] );
		}
		if  ( isset( $datos['picture'] ) ){
			$sth->bindValue(':picture', $datos['picture'] );
		}
		if  ( isset( $datos['originalName'] ) ){
			$sth->bindValue(':originalName', $datos['originalName'] );
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
		
		
		
		
		$paginaMod = new paginaModelo();
		foreach( $datos['paginasDeAutor'] as $el ){
			$el['autor']=$idObj;
			$res=$paginaMod->guardar($el);
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