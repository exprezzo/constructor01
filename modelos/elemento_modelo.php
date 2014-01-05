<?php
class elementoModelo extends Modelo{
	var $tabla="constructor_elemento_catalogo";
	var $campos=array('id','esDefault','extras','campo','llave','esNulo','tipo','componente','comp_config','fk_catalogo');
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