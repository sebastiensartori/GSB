<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		$visiteur = $pdo->getInfosVisiteur($login,md5($mdp));
		if(!is_array( $visiteur)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
                        $comptable = $visiteur['comptable'];
			connecter($id,$nom,$prenom,$comptable);
                        
                        if($_SESSION['comptable'] == '0')
                        {
                            include("vues/v_sommaire.php");
                        }
                        else
                        {
                            include("vues/v_sommaireC.php");
                        }
		}
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>