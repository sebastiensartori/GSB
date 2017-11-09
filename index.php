<?php
session_start();
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
include("vues/v_entete.php") ;
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}	 
$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 
	}
        case 'validerFrais' :{
            include("controleurs/c_validerFrais.php");break;
        }
        case 'gererPaiement' :{
            include("controleurs/c_suivrePaiement.php");break;
        }
        case 'pdf' :{
            include("controleurs/c_pdf.php");break;
        }
}
include("vues/v_pied.php") ;
?>

