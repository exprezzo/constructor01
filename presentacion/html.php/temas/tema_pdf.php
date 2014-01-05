<?php 
require_once '../php_libs/tcpdf/tcpdf.php';
class TemaPdf extends TCPDF{
	
	function imprimir(  ){
		$datos=$this->datos;
		$w=0;
		$h = 0;
		$txt = '';
		$border = 0;
		$ln = 0;
		$align = '';
		$fill = false;
		$link = '';
		$stretch = 0;
		$ignore_min_height = false;
		$calign = 'T';
		$valign = 'M' ;
		// $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);		
		$lblW=24;
		
		//-----------------------
		// {CAMPOS}
		//-----------------------
	}
	
	function header(){
		$datos=$this->datos;
		$w=0;
		$h = 0;
		$txt = '';
		$border = 0;
		$ln = 0;
		$align = '';
		$fill = false;
		$link = '';
		$stretch = 0;
		$ignore_min_height = false;
		$calign = 'T';
		$valign = 'M' ;
		// $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
		
		$txt= 'Tema: '.$this->datos['nombre'].'' ;		
		$this->titulo=$txt;
		$this->Cell($w, $h, $txt, $border, $ln, 'C', $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
	}
}
?>