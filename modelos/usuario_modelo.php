<?php
class UsuarioModelo extends Modelo{
	var $tabla="system_users";
	var $campos=array('id','nick','pass','email','rol','fbid','name','picture','originalName');
	var $pk="id";
	
	function nuevo($params){
		return parent::nuevo($params);
	}
	function guardar($params){
		return parent::guardar($params);
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