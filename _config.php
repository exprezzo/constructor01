<?php
$DB_CONFIG=array(
	'DB_SERVER'	=>'localhost',
	'DB_NAME'	=>'constructor',
	'DB_USER'	=>'root',
	'DB_PASS'	=>'',
	'PASS_AES'	=>'9as43s'
);

if ( !isset($APP_CONFIG) ) $APP_CONFIG = array();
if ( !isset($_DEFAUL_LAYOUT) ) $_DEFAUL_LAYOUT ='sistema';
$_DEFAUL_LAYOUT ='sistema';

global $_PETICION;
$APP_CONFIG['_LOGIN_REDIRECT_PATH']=$_PETICION->modulo.'/usuarios/login';
$APP_CONFIG['nombre'] = 'Portal';



?>