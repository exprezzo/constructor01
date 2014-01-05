<?php
class GeneradorControlador{
	var $funcionesCombo=array();
	function getRequire($modelo){
		return '
require_once $_PETICION->basePath.\'/modelos/'.$modelo.'.php\';';
	}
	
	function generarCodigoCombo( $modelo ){
		if ( in_array($modelo, $this->funcionesCombo) ) return '';
		
		$this->funcionesCombo[]=$modelo;
		
		
		$modMay = ucfirst( $modelo );
		$codigo='
		function buscar'.$modMay.'(){
			$'.$modelo.'Mod= new '.$modelo.'Modelo();
			$res = $'.$modelo.'Mod->buscar( array() );
			echo json_encode( $res );
		}
		';
		return $codigo;
	}
	
	function generar($cat, $rutaBase){
		// CREAR CONTROLADOR
		$directorio = $rutaBase.'controladores/';
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		$filename = dirname(__FILE__).'/plantillas/controlador.php';
		$handle = fopen($filename, "r");
		$conStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$conStr=$this->generarCodigo( $conStr, $cat );
		//---------------------------------------NUEVO
		$filename = $directorio.$cat['controlador'].'.php';
		if ( !file_exists( $filename ) ){
			$handle = fopen($filename, "w");
			$conStr= fwrite($handle, $conStr, strlen($conStr));
			fclose($handle);
		}
		
		//----------------------------------------------		
		return array(
			'success'=>true,
			'msg'=>'ContGen. Generado '
		);
	}
	function generarCodigo( $conStr, $cat ){
	
		$conStr = str_replace('{MODELO}', $cat['modelo'], $conStr);
		$conStr = str_replace('class Controlador', 'class '.$cat['controlador'], $conStr);
		$conStr = str_replace('$objPdf = new ModeloPdf', '$objPdf = new '.ucfirst($cat['modelo']).'Pdf', $conStr);
		
		
		$crlf = "\r\n";
		$strRequire=$this->getRequire( $cat['modelo'] );
		$strRequire.=$crlf.'require_once $_PETICION->basePath.\'/presentacion/html.php/'.$cat['controlador'].'/'.$cat['modelo'].'_pdf.php\';';
		
		$codigoCombo='';
		foreach($cat['elementos'] as $el){
			$config=json_decode($el['comp_config'], true);

			if ( strtolower( $el['componente'] ) ==  'combo box' ){
			
				$fk_catalogo=$config['target'];	
				$catMod=new catalogoModelo();
				$resCat=$catMod->obtener( array('id'=>$fk_catalogo) );
				$modeloObj['nombre'] = $resCat['modelo'];
				$modeloObj['tabla'] = $resCat['tabla'];
				$modeloObj['llave_primaria'] = $resCat['pk_tabla'];
				
				$strRequire .=$crlf.$this->getRequire( $modeloObj['nombre'] );
				
				$codigoCombo .= $this->generarCodigoCombo( $modeloObj['nombre'] );
			}	else if ( strtolower( $el['componente'] ) ==  'tabla' ){
				// print_r($config);
				$fk_catalogo=$config['target'];
				$catMod = new catalogoModelo();
				$cat_obj =$catMod->obtener( array('id'=>$fk_catalogo)  );
				// print_r( $cat_obj );
				$strRequire .=$crlf.$this->getRequire( $cat_obj['modelo'] );
			}			
		}
		
		$conStr = str_replace('//{REQUIRE-CLASES}', $strRequire.$crlf, $conStr);
		$conStr = str_replace('//{FUNCIONES-COMBO}', $codigoCombo, $conStr);
		
		return  $conStr;
		
	}
	
}
?>