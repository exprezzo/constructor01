<?php

require_once $_PETICION->basePath.'/modelos/Usuario.php';

class usuarios extends Controlador{
	var $modelo="Usuario";	
	
	var $accionesPublicas=array('login');
	
	function logout(){
		// global $_TEMA_APP, $_PETICION;
		
		// unset( $_SESSION['isLoged'] );
		// unset( $_SESSION['user'] );
		// session_unset();
		 // session_destroy();
		// session_start();
		// session_regenerate_id(true);
		logout();
		global $_PETICION;		
		header('Location: '.$_PETICION->url_app.$_PETICION->modulo.'/usuarios/login');
	}
	function login(){
		$vista= $this->getVista();
		global $_TEMA_APP, $_PETICION;		
		$layout='../constructor/presentacion/login';
		if ( $_SERVER['REQUEST_METHOD']=='POST'  ){
			//Login con ajax o el post del form 
			$usrMod = $this->getModelo();
			$res = $usrMod->identificar($_POST['nick'], $_POST['pass']);			
			
			
			if ($res['success']){
				// isLoged(true);					
				// addUser( $res['usuario'] );
				sessionAdd('isLoged', true);
				
				unset($res['usuario']['pass']);					
				sessionAdd('user', $res['usuario']);
				// $_SESSION['user']=$res['usuario'];
							
					// if ($_SESSION['user']['fk_rol'] == 1 ){				
						// $_SESSION['isLoged']=true;								
						// header('Location:'.$_PETICION->url_app.'usuarios/buscar');
					// }else{
						// Se reconecta con la bd que le corresponde
						
						// if ( sizeof( $res['corps'] )==1 ){
							// $_SESSION['isLoged']=true;		
							// $corp=$res['corps'][0];
							// $corpMod = new corporativoModelo();
							// $paramsCorp=array( 'id'=> $corp['fk_corporativo'] );
							// $corpArr = $corpMod->obtener( $paramsCorp);							
							// $_SESSION['DB_CONFIG']=$corpArr;							
						// }else{							
							// echo 'SELECCIONE UN CORPORATIVO'; exit;
						// }
						// header('Location:'.$_PETICION->url_app.'facturas/emitidas');
					// }					
					$url=sessionGet('_PETICION');
					$url=( empty($url) ) ? $_PETICION->url_app.$_PETICION->modulo.'/paginas/inicio' : '/'.$url;
					// echo $url; exit;
					 header('Location:'.$url);
			}else{				
				$vista->error= $res['msg'];//'USUARIO O CONTRASEÑA INCORRECTA';
				$vista->username=$_POST['nick'];
				return $vista->mostrarTema($_PETICION, $_TEMA_APP, $layout);
			}			
		}else{
			//PETICION GET U OTRA DIFERENTE DE POST			
			$vista->username='';
			return $vista->mostrarTema($_PETICION, $_TEMA_APP, $layout);
		}				
	}
	
	function mostrarVista( $archivos=""){
		$vista= $this->getVista();
		
		global $_TEMA_APP;
		global $_PETICION;
		return $vista->mostrarTema($_PETICION, $_TEMA_APP);
	}
	
	function nuevo(){		
		$modelo = $this->getModelo();
		$obj=$modelo->nuevo( array() );
		
		$vista=$this->getVista();
		$vista->datos=$obj;		
		
		global $_TEMA_APP;
		global $_PETICION;
		$_PETICION->accion="edicion";
		return $vista->mostrarTema($_PETICION, $_TEMA_APP);
		
	
	}
	
	function guardar(){
		$modelo=$this->getModelo();
		$esNuevo = empty( $_POST['datos'][$modelo->pk] );
		global $_PETICION;
		$id=$_POST['datos'][$modelo->pk];
		
		if ( ( !empty($_POST['datos']['pass']) || !empty($_POST['datos']['confirmacion']) ) && ($_POST['datos']['pass'] != $_POST['datos']['confirmacion']) ){
			$respuesta = array(
				'success'	=>false,
				'msg'		=>'Las contraseñas no coinciden'
			);
			echo json_encode( $respuesta );
			return $respuesta;
		}
		
		$user=sessionGet('user');
		if ( $user['fk_rol'] != 1 && $user['id'] != $id ){
			$respuesta = array(
				'success'	=>false,
				'msg'		=>'No tiene permiso para editar este usuario',
				'titulo'	=>'Mensaje de la capa de seguridad'
			);
			
			echo json_encode($respuesta);
			return $respuesta;
		}
		ob_start();
		$res = parent::guardar();
		ob_end_clean();
		
		if ( !$res['success'] ){			
			echo json_encode($res);
			return $res;
		}
		
		if ( $esNuevo ){					
			$res['esNuevo']=true;				
			// $_SESSION['res']=$res;
			sessionSet('res', $res);
		}
		echo json_encode($res);
		return $res;
	}
	function eliminar(){
		return parent::eliminar();
	}
	function editar(){
		global $_PETICION;
		// print_r($_PETICION);
		if ( isset($_PETICION->params[0]) )
		$_REQUEST['id'] = $_PETICION->params[0];
		
		// return parent::editar();
		$id=empty( $_REQUEST['id'])? 0 : $_REQUEST['id'];
		$model=$this->getModelo();
		$params=array(
			$model->pk=>$id
		);		
		
		$obj=$model->obtener( $id );			
		$vista=$this->getVista();				
		$vista->datos=$obj;		
		
		global $_PETICION;
		global $_TEMA_APP;
		$_PETICION->accion="edicion";
		$vista->mostrarTema($_PETICION,$_TEMA_APP);
	}
	function buscar(){
		if ( $_SERVER['REQUEST_METHOD']=='POST'  ){
			return parent::buscar();			
		}else{
			global $_PETICION, $_TEMA_APP;
			$vista = $this->getVista();
			$_PETICION->accion='busqueda';
			return $vista->mostrarTema($_PETICION, $_TEMA_APP);
		}
	}
}
?>