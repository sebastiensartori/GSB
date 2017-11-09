<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch ($action){
    case 'voirPDF':
        require (dirname(__FILE__).'\..\fpdf\fpdf.php');
        $idutilisateur=$_REQUEST['id'];
        $mois=$_REQUEST['mois'];
        include("vues/v_pdfFicheFrais.php");        
        break;
}
