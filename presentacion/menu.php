<?php
	
	require_once '../'.$_PETICION->modulo.'/modelos/catalogo_modelo.php';
	require_once '../'.$_PETICION->modulo.'/modelos/modulo.php';
	
	$moduMod = new moduloModelo();
	$modulos = $moduMod->buscar( array() );		
	$modulos = $modulos['datos'];
	
	$catMod = new catalogoModelo();
	

	// print_r($modulos);
	for($i=0; $i<sizeof($modulos) ; $i++ ){
		$params = array(
			'filtros'=>array(
				array(
					'dataKey'=>'fk_modulo',
					'filterOperator'=>'equals',
					'filterValue'=>$modulos[$i]['id']
				)
			)
		);
		$catalogos = $catMod->buscar( $params );		
		$modulos[$i]['catalogos']=$catalogos['datos'];
	}
	
	
	// 

?>
<ul class="nav">                    	
	<li>
		<a href="#">Catálogos<span class="flecha">∨</span></a>
		<ul>
			<?php
			foreach($modulos as $mod){
				// print_r($mod);
				 echo '<li><a href="#" >'.$mod['nombre'].'</a><ul>';
				foreach($mod['catalogos'] as $cat){
					// print_r($cat);
					echo '<li><a href="'.$_PETICION->url_app.$mod['nombre_interno'].'/'.$cat['controlador'].'/buscar" class="elemento">'.$cat['nombre'].'<span class="flecha">∨</span></a></li>';
				}
				echo '</ul></li>';
			}
			
			?>
		</ul>
	</li>
	<li>
		<a href="#">Configuración<span class="flecha">∨</span></a>
		<ul>
			<li><a href="<?php echo $_PETICION->url_app; ?>usuarios/buscar" class="elementoTop">Usuarios<span class="flecha">∨</span></a></li>	
		</ul>
	</li>                                        
	<li>
		<a href="#">Ayuda<span class="flecha">∨</span></a>
		
		
	</li>
</ul>