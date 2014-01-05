<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Constructor</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_PETICION->url_web; ?>estilos/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_PETICION->url_web; ?>estilos/estilo1.css" />
<script type="text/javascript" src="<?php echo $_PETICION->url_web; ?>js/jquery-1.8.3.js" ></script>	
<script type="text/javascript" src="<?php echo $_PETICION->url_web; ?>js/funciones.js" ></script>	
<script type="text/javascript" src="<?php echo $_PETICION->url_web; ?>js/navegacion_en_tabla.js" ></script>	
<script src="<?php echo $_PETICION->url_web; ?>libs/jquery-ui-1.9.2.custom/jquery-ui-1.9.2.custom.js"></script>  
<script src="<?php echo $_PETICION->url_web; ?>libs/blockui.js"></script>  
<style>
	.blockUI h1{color:black;} 
	#contenedorDatos2 > .ui-widget.wijmo-wijgrid {z-index:0; }
	div[role="combobox"] input[role="textbox"]{border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; height:21px !important; font-size:1em !important; }
	div[role="combobox"] .wijmo-wijcombobox-trigger{height:31px !important;}
</style>
<!--Wijmo Widgets CSS-->	
	<link id="linkCss" href="<?php echo $_PETICION->url_web; ?>libs/Wijmo.2.3.2/Wijmo-Complete/css/jquery.wijmo-complete.2.3.2.css" rel="stylesheet" type="text/css" />
	<?php
	// $rutaTema='http://cdn.wijmo.com/themes/aristo/jquery-wijmo.css'; 
	 $rutaTema=sessionGet('rutaTema');
	 $user=sessionGet('user');
	 // echo $rutaTema;
	 if (empty($rutaTema)){
		$rutaTema=getUrlTema('midnight');
	 }
	 
	 
	// $rutaTema=getUrlTema('rocket'); 	
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
			
			$('.rzSel').bind('click',function(){
				var $rzId = $(this).attr('rzId');				
				selecionarRfc($rzId);
			});
			
			switch(kore.controlador){
				case 'paginas':
					if (kore.accion=='inicio'){
						$('#contenedorMenu > ul > li:nth-child(2) > a:nth-child(1)').addClass("estiloFactura");
					}else if(kore.accion=='ayuda'){
						$('#contenedorMenu > ul > li:nth-child(4) > a:nth-child(1)').addClass("estiloFactura");
					}else if(kore.accion=='documentacion'){
						$('#contenedorMenu > ul > li:nth-child(4) > a:nth-child(1)').addClass("estiloFactura");
					}
				break;
				case 'facturas':					
					$('#contenedorMenu > ul > li:nth-child(1) > a:nth-child(1)').addClass("estiloFactura");
				break;
				case 'conceptos':
				case 'receptores':
				case 'rfcs':					
					$('#contenedorMenu > ul > li:nth-child(2) > a:nth-child(1)').addClass("estiloFactura");
					break;
				case 'series':					
				case 'paises':					
				case 'estados':					
				case 'ciudades':					
				case 'municipios':					
					$('#contenedorMenu > ul > li:nth-child(3) > a:nth-child(1)').addClass("estiloFactura");
				break;			
			}
		});
		
		function selecionarRfc(id){			
			var params={};
			params['id']=id;			
			$.ajax({
					type: "POST",
					url: kore.url_base+kore.modulo+'/paginas/seleccionarRfz',
					data: params
				}).done(function( response ) {		
					var resp = eval('(' + response + ')');
					var msg= (resp.msg)? resp.msg : '';
					var title;
					if ( resp.success == true	){
						icon=kore.url_web+'imagenes/yes.png';
						title= 'Success';						
						$('#valRfc').html(resp.datos.nombre_comercial);
					}else{
						icon= kore.url_web+'imagenes/error.png';
						title= 'Error';
					}
					
					//cuando es true, envia tambien los datos guardados.
					//actualiza los valores del formulario.
					$.gritter.add({
						position: 'bottom-left',
						title:title,
						text: msg,
						image: icon,
						class_name: 'my-sticky-class'
					});
				});
		}
	</script>
</head>
<body class="widgets" >
	<div class="widget-list">

<div id="global" class="widget-inner ui-widget-overlay" style="opacity:1;">

    <div id="encabezado">
    	<img src="<?php echo $_PETICION->url_web; ?>img/logo.png" id="logo">
		</img>
		<div style="position:absolute; display:none; width:200px;">
			<?php
				$modTema=getModelo( 'tema' );
				$params=array();
				$temasRes = $modTema->buscar( $params );
				$temas = $temasRes['datos'];				
			?>
			<select id="comboTemas">
				<?php foreach($temas as $tema){
					echo '<option value="'.$tema['ruta'].'">'.$tema['nombre'].'</option>';
				} 
				
				?>
			</select>
		</div>
        <div id="contenedorMenu">
            <?php 
				
					include 'menu.php';
				
			
			?>
		</div>
		
		
        <div class="contenedorDatos1" style="margin-top: 11px; background-color: transparent;display: inline-block;right: 17px;position: absolute;">         
			
                    
                    <!--<br /><br />-->
                    <label class="datos1" style="clear:both; float:none; display:inline-block; vertical-align:top;">USER</label>
                    <ul class="nav" style="display:inline-block;clear:both;">  
						<li>
							<a href="<?php echo $_PETICION->url_app.$_PETICION->modulo.'/usuarios/editar/'.$user['id']; ?> " class="estiloFactura">Perfil<span class="flecha"> ∨</span></a>
							<ul>
								<li><a class="elementoTop" href="<?php echo $_PETICION->url_app.$_PETICION->modulo.'/usuarios/editar/'.$user['id']; ?>" class="">Editar Mi Perfil<span class="flecha">∨</span></a></li>
								<li><a class="elementoBottom" href="<?php echo $_PETICION->url_app.$_PETICION->modulo; ?>/usuarios/logout" class="">Salir del sistema<span class="flecha">∨</span></a></li>
							</ul>
						</li>
                    </ul>
                    
                 

                    
        </div>
        
		<form name="busqueda" action="<?php echo $_PETICION->url_app.$_PETICION->modulo.'/'.$_PETICION->controlador; ?>/buscar" method="get" id="contenedorBusqueda" style="position:absolute;bottom: 16px;right: 18px;">
		<input type="text" name="query" id="barraBusqueda" value="<?php echo empty($_GET['query'])? '' : $_GET['query']; ?>">
		<input type="submit" value=" " id="botonBusqueda">
		</form> 
	</div>
	<div id="tabs"  class="" >
    <?php $this->mostrar() ?>
    </div>
    
    <div id="pie">
    	<div id="contenedorMenu4">
        <ul>
        	<li><a href="<?php echo $_PETICION->url_app;?>paginas/inicio">Inicio</a></li>            
            <li>Ayuda</li>
        </ul>
        </div>
    </div>
    
</div>
	</div>
</body>
<script type="text/javascript">
	$(function(){
		
		$('#contenedorDatos2').addClass('ui-widget-content');		
		$('#titulo').addClass('ui-widget-header');		
		$('input[type="text"]').wijtextbox();
		$('#contenedorMenu').addClass('ui-widget-header');
		$('#contenedorMenu').css('border', 'none');
		$('#contenedorMenu').css('background', 'none');		 
		 
		$('#comboTemas').wijcombobox({
			select:function(e, val){				
				// $('#linkCss').attr('href',val.value);
				var datos={rutaTema: val.value};
				$("#contenedorDatos2").block({ 
					message: '<h1>Guardando Tema</h1>'               
				}); 
				$.ajax({
					type: "POST",
					url: kore.url_base+kore.modulo+'/paginas/setTema',
					data: { datos: datos}
				}).done(function( response ) {
					$("#contenedorDatos2").unblock(); 
					location.reload();
					var resp = eval('(' + response + ')');
					var msg= (resp.msg)? resp.msg : '';
					var title;
					
					if ( resp.success == true	){
						if (resp.msgType!=undefined && resp.msgType == 'info'){
							icon=kore.url_web+'imagenes/yes.png';
						}else{
							icon=kore.url_web+'imagenes/info.png';
						}
						
						
						title= 'Success';				
						
						
						$.gritter.add({
							position: 'bottom-left',
							title:title,
							text: msg,
							image: icon,
							class_name: 'my-sticky-class'
						});
						
						if (me.saveAndClose===true){
							//busca el indice del tab
							var idTab=$(me.tabId).attr('id');
							var tabs=$('#tabs > div');
							for(var i=0; i<tabs.length; i++){
								if ( $(tabs[i]).attr('id') == idTab ){
									$('#tabs').wijtabs('remove', i);
								}
							}
						}
					}else{
						icon= kore.url_web+'imagenes/error.png';
						title= 'Error';					
						$.gritter.add({
							position: 'bottom-left',
							title:title,
							text: msg,
							image: icon,
							class_name: 'my-sticky-class'
						});
					}
					
					//cuando es true, envia tambien los datos guardados.
					//actualiza los valores del formulario.
					
				});
			}
		})
		
	});
</script>
</html>
<?php
function getUrlTema($tema){
	$_TEMAS=array();
	global $_PETICION;
	// print_r($_PETICION);
	$_TEMAS['artic']="http://cdn.wijmo.com/themes/arctic/jquery-wijmo.css";
	$_TEMAS['midnight']="http://cdn.wijmo.com/themes/midnight/jquery-wijmo.css";
	$_TEMAS['aristo']="http://cdn.wijmo.com/themes/aristo/jquery-wijmo.css";
	// $_TEMAS['rocket']="http://cdn.wijmo.com/themes/rocket/jquery-wijmo.css";
	$_TEMAS['rocket']=$_PETICION->url_web. "css/jquery-wijmo_rocket.css";
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