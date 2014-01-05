<?php 
require dirname(__FILE__).'\generador_modelo.php';
require dirname(__FILE__).'\generador_controlador.php';
require dirname(__FILE__).'\generador_formulario.php';
require dirname(__FILE__).'\generador_busqueda.php';
require dirname(__FILE__).'\generador_pdf.php';
class GeneradorDeCodigo{
	
	function generarCodigo($catalogo_id){
		
		//Obtengo el catalogo almacenado en el servidor
		$catMod = new CatalogoModelo();
		$cat = $catMod->obtener( array('id'=>$catalogo_id ) );
		$fk_modulo = $cat['fk_modulo'];
		
		// compone la ruta donde se guardaran los archivos
		$modMod = new moduloModelo();
		$modulo = $modMod->obtener( $fk_modulo  );		
		$rutaBase='..'.$modulo['ruta_base'].$modulo['nombre_interno'].'/';
				
		//crea el modelo
		$modGen = new GeneradorModelo();
		$res=$modGen->generar($cat, $rutaBase);
		if ( !$res['success'] ){
			return $res;
		}
		//crea el controlador
		$generadorControlador = new GeneradorControlador();
		$res = $generadorControlador->generar($cat, $rutaBase);
		if ( !$res['success'] ){
			return $res;
		}
		
		//crea el formulario 
		$formGen = new GeneradorFormulario();
		$res  = $formGen->generar($cat, $rutaBase);
		if ( !$res['success'] ){
			return $res;
		}
		
		
		//crea la busqueda 		
		$busGen = new GeneradorBusqueda();
		$res  = $busGen->generar($cat, $rutaBase);
		if ( !$res['success'] ){
			return $res;
		}
		
		//crea el formulario 
		$formGen = new GeneradorPdf();
		$res  = $formGen->generar($cat, $rutaBase);
		
		
		return $res;
	}
	
	
}
?>