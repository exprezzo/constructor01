<?php
class paginaModelo extends Modelo{
	var $tabla="system_pagina";
	var $campos=array('id','titulo','nombre','contenido','fk_categoria_pagina','fecha_creacion','ultima_edicion');
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