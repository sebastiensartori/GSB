<?php

include("vues/v_sommaireC.php");
$action = $_REQUEST['action'];
switch ($action) {
    
    case 'choisirMois':
        $lesMois = $pdo->getLesMoisCL();
        include 'vues/v_listeMoisC.php';
        break;
    
    
    case 'voirVisiteurFrais':
       
        $moisASelectionner = $_REQUEST['lstMois'];
        
        
        $lesMois = $pdo->getLesMoisCL();
        include("vues/v_listeMoisC.php");
        
        
        $lesVisiteurs = $pdo->getLesVisiteursCL($moisASelectionner);
        include 'vues/v_listeVisiteur.php';
        break;
    
    
    case 'voirFicheFrais':
        
        $moisASelectionner = $_REQUEST["mois"];
        $visiteurASelectionner = $_REQUEST['lstVisiteur'];
        
        
        $lesMois = $pdo->getLesMoisCL();
        include("vues/v_listeMoisC.php");
        
        
        $lesVisiteurs = $pdo->getLesVisiteursCL($moisASelectionner);
        include 'vues/v_listeVisiteur.php';

        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurASelectionner, $moisASelectionner);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurASelectionner, $moisASelectionner);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurASelectionner, $moisASelectionner);
        $numAnnee = substr($moisASelectionner, 0, 4);
        $numMois = substr($moisASelectionner, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = $lesInfosFicheFrais['dateModif'];
        $dateModif = dateAnglaisVersFrancais($dateModif);
        
        include 'vues/v_validation.php';
        break;
    
    
    case 'modifier':
        
        
        $moisASelectionner = $_REQUEST["mois"];
        $visiteurASelectionner = $_REQUEST['idVisiteur'];
        
        
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurASelectionner, $moisASelectionner);

        include 'vues/v_modifierFraisF.php';
        break;
    
    
    case 'modifierFraisF':
        
        
        $moisASelectionner = $_REQUEST['leMois'];
        $visiteurASelectionner = $_REQUEST['leVisiteur'];
        $lesFrais = $_REQUEST['lesFrais'];
        
        
        if(lesQteFraisValides($lesFrais)){
            $pdo->majFraisForfait($visiteurASelectionner,$moisASelectionner,$lesFrais);
        }
       
        header('Location: index.php?uc=validerFrais&action=voirFicheFrais&lstVisiteur='.$visiteurASelectionner.'&mois='.$moisASelectionner);
        break;
        
    
    case 'validerFiche' :
        
        
        $moisASelectionner = $_REQUEST['mois'];
        $visiteurASelectionner = $_REQUEST['idVisiteur'];
        $numAnnee = substr($moisASelectionner, 0, 4);
        $numMois = substr($moisASelectionner, 4, 2);
        
        
        
        
        include 'vues/v_comptableValidation.php';
        
        break;
    
    
    case 'reporter':{
        
        
        $idFraisHorsForfait = $_REQUEST['hdIdFraisHorsForfait'];
        $moisASelectionner = $_SESSION['leMois'];
        $visiteurASelectionner = $_SESSION['leVisiteur'];
        $dernierMois = $pdo->dernierMoisSaisi($visiteurASelectionner);        
        if($moisASelectionner == $dernierMois)
        {
            $dernierMois = incrementerMois($moisASelectionner);
            $pdo->creeNouvellesLignesFrais($visiteurASelectionner, $dernierMois);
            $pdo->reportDUnFraisHorsForfait($idFraisHorsForfait,$dernierMois);
        }
        else
        {
            $pdo->reportDUnFraisHorsForfait($idFraisHorsForfait,$dernierMois);
        }
        header('Location: index.php?uc=selectionnerFicheFrais&action=voirFicheFrais');
        break;
    }
    case 'refuser':{
        
        $idFraisHorsForfait=$_REQUEST['hdIdFraisHorsForfait'];
        $moisASelectionner = $_SESSION['leMois'];
        $visiteurASelectionner = $_SESSION['leVisiteur'];
        $pdo->refuserFraisHorsForfait($idFraisHorsForfait);
        header('Location: index.php?uc=selectionnerFicheFrais&action=voirFicheFrais');
        break;
    }
    
    
    
        
    
}

