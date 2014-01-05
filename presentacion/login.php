<?php
if ( isLoged() ){				
	$url=sessionGet('_PETICION');
	$url=( empty($url) ) ? $_PETICION->url_app.$_PETICION->modulo.'/'.'paginas/inicio' : $url;
	header('Location:'.$url);	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FACTURA</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_PETICION->url_web; ?>estilos/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_PETICION->url_web; ?>estilos/estilo_login.css" />
<script type="text/javascript" src="<?php echo $_PETICION->url_web; ?>js/jquery-1.8.3.js" ></script>	
<script type="text/javascript" src="<?php echo $_PETICION->url_web; ?>js/funciones.js" ></script>	
<script src="<?php echo $_PETICION->url_web; ?>libs/jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.js"></script>  

<!--Wijmo Widgets CSS-->	
	<link href="<?php echo $_PETICION->url_web; ?>libs/Wijmo.2.3.2/Wijmo-Complete/css/jquery.wijmo-complete.2.3.2.css" rel="stylesheet" type="text/css" />
	<?php
	// $rutaTema='http://cdn.wijmo.com/themes/aristo/jquery-wijmo.css'; 
	// $rutaTema=getUrlTema('midnight'); 
	if  ( $_PETICION->controlador=='facturas' && ($_PETICION->accion =='edicion' || $_PETICION->accion =='nueva' || $_PETICION->accion =='nuevo') ){
		$rutaTema=''; 		
		$rutaTema=getUrlTema('rocket'); 				  
	}else{
		$rutaTema=getUrlTema('midnight'); 
		// $rutaTema=''; 		
		// $rutaTema=getUrlTema('rocket'); 
		// $rutaTema=''; 		
	}
	
	?>
	<link href="<?php echo $rutaTema; ?>" rel="stylesheet" type="text/css" />
	
	<link href="<?php echo $_PETICION->url_web; ?>libs/Wijmo.2.3.2/Wijmo-Open/css/jquery.wijmo-open.2.3.2.css" rel="stylesheet" type="text/css" />			
	<!--link href="/css/themes/blitzer/jquery-ui-1.9.2.custom.css" rel="stylesheet"-->	
	<!--Wijmo Widgets JavaScript-->
	<script src="<?php echo $_PETICION->url_web; ?>libs/Wijmo.2.3.2/Wijmo-Complete/js/jquery.wijmo-complete.all.2.3.2.min.js" type="text/javascript"></script>
	<script src="<?php echo $_PETICION->url_web; ?>libs/Wijmo.2.3.2/Wijmo-Open/js/jquery.wijmo-open.all.2.3.2.min.js" type="text/javascript"></script>	
	
	<!-- Gritter -->
	<link href="<?php echo $_PETICION->url_web; ?>libs/Gritter-master/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $_PETICION->url_web; ?>libs/Gritter-master/js/jquery.gritter.min.js" type="text/javascript"></script>
	<script src="<?php echo $_PETICION->url_web; ?>libs/jquery.validate.js"></script>
	<script type="text/javascript">		
		kore={
			modulo:'<?php echo $_PETICION->modulo; ?>',
			controlador:'<?php echo $_PETICION->controlador; ?>',
			accion:'<?php echo $_PETICION->accion; ?>',
			url_base:'<?php echo $_PETICION->url_app; ?>',			
			url_web:'<?php echo $_PETICION->url_web; ?>',
			decimalPlacesMoney:2
		};
		
		$(function(){
			$.extend($.gritter.options, {
				position: 'bottom-right',
				fade_in_speed: 'medium', // how fast notifications fade in (string or int)
				fade_out_speed: 2000, // how fast the notices fade out
				time: 6000 // hang on the screen for...
			});
			
			
			switch(kore.controlador){
				case 'paginas':					
					if (kore.accion=='inicio'){
						$('#contenedorMenu > ul > li:nth-child(1) > a:nth-child(1)').addClass("estiloFactura");
					}else if(kore.accion=='login'){
						$('#contenedorMenu > ul > li:nth-child(2) > a:nth-child(1)').addClass("estiloFactura");
					}
				break;				
			}
		});
		
		
	</script>
</head>

<body>

<div id="global">

    <div id="encabezado" style="">
    	<img src="<?php echo $_PETICION->url_web; ?>img/logo.png" id="logo">
		</img>
        <div id="contenedorMenu">
                    <ul class="nav">
                    <li>
                        <a href="<?php echo $_PETICION->url_app; ?>paginas/inicio">Inicio<span class="flecha">∨</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $_PETICION->url_app; ?>paginas/login">Login<span class="flecha">∨</span></a>
                    </li>
                </ul>
		</div>
		
		
        
        
	</div>
	<div id="tabs">
    <?php $this->mostrar() ?>
    </div>
    
    <div id="pie">
    	<div id="contenedorMenu4">
        <ul>
        	<li>Inicio</li>
            <li>Facturas</li>
            <li>Catálogos auxiliares</li>
            <li>Ayuda</li>
        </ul>
        </div>
    </div>
    
</div>

</body>
</html>
<?php
function getUrlTema($tema){
	$_TEMAS=array();
	global $_PETICION;
	// print_r($_PETICION);
	$_TEMAS['artic']="http://cdn.wijmo.com/themes/arctic/jquery-wijmo.css";
	$_TEMAS['midnight']="http://cdn.wijmo.com/themes/midnight/jquery-wijmo.css";
	$_TEMAS['aristo']="http://cdn.wijmo.com/themes/aristo/jquery-wijmo.css";
	$_TEMAS['rocket']="http://cdn.wijmo.com/themes/rocket/jquery-wijmo.css";
	// $_TEMAS['rocket']=$_PETICION->url_web_mod. "libs/temas_wijmo/rocket/jquery-wijmo.css";
	$_TEMAS['cobalt']="http://cdn.wijmo.com/themes/cobalt/jquery-wijmo.css";
	$_TEMAS['sterling']="http://cdn.wijmo.com/themes/sterling/jquery-wijmo.css";
	$_TEMAS['black-tie']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/black-tie/jquery-ui.css";
	$_TEMAS['blitzer']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/blitzer/jquery-ui.css";
	$_TEMAS['cupertino']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/cupertino/jquery-ui.css";
	$_TEMAS['dark-hive']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/dark-hive/jquery-ui.css";
	$_TEMAS['dot-luv']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/dot-luv/jquery-ui.css";
	$_TEMAS['eggplant']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/eggplant/jquery-ui.css";
	$_TEMAS['excite-bike']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/excite-bike/jquery-ui.css";
	$_TEMAS['flick']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/flick/jquery-ui.css";
	$_TEMAS['hot-sneaks']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/hot-sneaks/jquery-ui.css";
	$_TEMAS['humanity']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/humanity/jquery-ui.css";
	$_TEMAS['le-frog']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/le-frog/jquery-ui.css";
	$_TEMAS['mint-choc']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/mint-choc/jquery-ui.css";
	$_TEMAS['vader']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/vader/jquery-ui.css";
	$_TEMAS['ui-lightness']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/ui-lightness/jquery-ui.css";
	$_TEMAS['ui-darkness']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/ui-darkness/jquery-ui.css";
	$_TEMAS['trontastic']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/trontastic/jquery-ui.css";
	$_TEMAS['swanky-purse']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/swanky-purse/jquery-ui.css";
	$_TEMAS['sunny']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/sunny/jquery-ui.css";
	$_TEMAS['start']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/start/jquery-ui.css";
	$_TEMAS['south-street']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/south-street/jquery-ui.css";
	$_TEMAS['smoothness']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/smoothness/jquery-ui.css";
	$_TEMAS['redmond']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/redmond/jquery-ui.css";
	$_TEMAS['pepper-grinder']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/pepper-grinder/jquery-ui.css";
	$_TEMAS['overcast']="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/overcast/jquery-ui.css";
	return $_TEMAS[$tema];
}
?>