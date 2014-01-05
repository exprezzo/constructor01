<?php
class GeneradorPdf{
	
	
	function generar($cat, $rutaBase){
		// CREAR HTML
		$directorio = $rutaBase.'presentacion/html.php/'.$cat['controlador'].'/';
		if ( !file_exists($directorio)) {
			mkdir($directorio,'0777', true);
		}
		$filename = dirname(__FILE__).'/plantillas/modelo_pdf.php';
		$handle = fopen($filename, "r");
		$htmlStr= fread($handle, filesize($filename));
		fclose($handle);
		//------------------------------------------
		$pdfStr=$this->generarCodigo( $htmlStr, $cat, $rutaBase );
		//---------------------------------------
		$filename = $directorio.$cat['modelo'].'_pdf.php';
		
		//antes de escribir el html, revisa que no exista
		// if ( !file_exists( $filename ) ){
			$handle = fopen($filename, "w");
			$pdfStr= fwrite($handle, $pdfStr, strlen($pdfStr));
			fclose($handle);
		// }
		
		//----------------------------------------------
		
		
		return array(
			'success'=>true,
			'msg'=>'Pdf. Generado '
		);
	}
	
	function generarCodigo($pdfStr, $cat, $rutaBase){
		$pdfStr = str_replace('class ModeloPdf', 'class '.ucfirst($cat['modelo']).'Pdf', $pdfStr);
		
		//------------------
		$cadenaOrigen = $cat['titulo_edicion'];		 
		preg_match_all  ('/\{(.*?)\}/',$cadenaOrigen, $matches);
		
		if ($matches){ //SI SE ENCONTRARON ELEMENTOS POR REPLAZAR		
			for($im=0; $im<sizeof($matches[0]); $im++) { // SE REMPLAZA De UNO EN UNO
				$cadenaAremplazar = $matches[0][$im];
				$nombreCampo=$matches[1][$im];				
				// $valor=;			
				$remplazo='\'.$this->datos[\''.$nombreCampo.'\'].\'';
				$cadenaOrigen=str_replace($cadenaAremplazar, $remplazo, $cadenaOrigen);								
				
			}			
		}
		$cadenaTitulo='$txt= \''.$cadenaOrigen.'\' ;';
		
		$txt= '{TITULO}';
		$pdfStr = str_replace('$txt= \'{TITULO}\';', $cadenaTitulo, $pdfStr);
		//------------------
		
		
		return $pdfStr;
	}
		
}
?>