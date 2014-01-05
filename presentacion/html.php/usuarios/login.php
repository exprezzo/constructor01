<style>
	.error{color:red;}
</style>
<?php
	$fecha=new DateTime();	
	setlocale(LC_TIME, 'spanish');  
	$numMes = intval($fecha->format('m'));
	$nombreMes='';
	switch($numMes){
		case 1:
			$nombreMes='Enero';
		break;
		case 2:
			$nombreMes='Febrero';
		break;
		case 3:
			$nombreMes='Marzo';
		break;
		case 4:
			$nombreMes='Abril';
		break;
		case 5:
			$nombreMes='Mayo';
		break;
		case 6:
			$nombreMes='Junio';
		break;
		case 7:
			$nombreMes='Julio';
		break;
		case 8:
			$nombreMes='Agosto';
		break;
		case 9:
			$nombreMes='Septiembre';
		break;
		case 10:
			$nombreMes='Octubre';
		break;
		case 11:
			$nombreMes='Noviembre';
		break;
		case 12:
			$nombreMes='Diciembre';
		break;
	}
	// $nombreMes=strftime("%B",mktime(0, 0, 0, $fecha->format('m'), 1, 2000)); 	
	// $nombreMes=ucfirst($nombreMes);
	
?>
	<div id="titulo" style=" ">
    	<div id="contenedorCalendario">
        <span id="dia"><?php echo $fecha->format('d'); ?></span> <span id="mes"><?php echo $nombreMes ?></span> <span id="año" style="position: absolute; bottom: -2px; right: 60px;"><?php echo $fecha->format('Y'); ?></span>
    
		</div>
	</div>
    
    <div id="cuerpo">
    	<div id="contenedorLogin">
			
            <p>
            Usuario
            </p> 
            <form name="login" action="<?php echo $_PETICION->url_app.$_PETICION->modulo; ?>/usuarios/login#cuerpo" method="POST">
                    <input type="text" name="nick" value="<?php echo $this->username; ?>" class="textoLogin" placeholder="Username" required>
                    <input type="password" name="pass" value="" class="textoLogin" placeholder="Password" required>
                    <br />
					<?php
						if ( !empty($this->error) ){
					?>
						<div class="error">
							<?php echo utf8_encode($this->error); ?>
						</div>
					<?php
						}
					?>
                    <input style="cursor:pointer;" type="submit" value="Login" id="botonLogin">
            </form> 
            
        </div> 
        
	</div>
