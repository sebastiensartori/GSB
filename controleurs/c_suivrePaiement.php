<?php
include("vues/v_sommaire.php");
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
    case 'selectionnerVisiteurs':
        $lesVisiteurs=PdoGsb::getLesVisiteursSuivi();
        $lesMois=PdoGsb::getLesMoisSuivi();
        include("vues/v_suivrePaiement.php");
        break;
    
    case 'listeFichesSuivi':
        $listeFiches=PdoGsb::verifExistanceFiche($_REQUEST['idVisiteur'], $_REQUEST['Mois']);
        $tabFrais = PdoGsb::getLesFraisForfaitSuivi($_REQUEST["idVisiteur"],$_REQUEST["Mois"]);
        $tabHorsForf = PdoGsb::getLesFraisHorsForfait($_REQUEST["idVisiteur"],$_REQUEST["Mois"]);
        include("vues/v_listeFichesSuivi.php");
        break;
    
    case 'validerPaiement':
        $idVisiteur=$_REQUEST['id'];
        $mois=$_REQUEST['mois'];
        $pdo->majEtatFicheFrais($idVisiteur,$mois, 'RB');
        include("vues/v_validationPaiement.php");
        break;
    
}
?>