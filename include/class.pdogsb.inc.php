<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=GSB';   		
      	private static $user='root' ;    		
      	private static $mdp='root' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un utilisateur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosutilisateur($login, $mdp){
		$req = "select utilisateur.id as id, utilisateur.nom as nom, utilisateur.prenom as prenom from utilisateur 
		where utilisateur.login='$login' and utilisateur.mdp='$mdp' and utilisateur.comptable = 1";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	static public function getLesFraisHorsForfait($idutilisateur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idutilisateur ='$idutilisateur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un utilisateur pour un mois donné
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idutilisateur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idutilisateur ='$idutilisateur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idutilisateur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idutilisateur ='$idutilisateur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un utilisateur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idutilisateur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idutilisateur = '$idutilisateur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le utilisateur concerné
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idutilisateur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idutilisateur = '$idutilisateur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un utilisateur possède une fiche de frais pour le mois passé en argument
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idutilisateur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idutilisateur = '$idutilisateur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un utilisateur
 
 * @param $idutilisateur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idutilisateur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idutilisateur = '$idutilisateur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un utilisateur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idutilisateur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idutilisateur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idutilisateur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idutilisateur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idutilisateur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idutilisateur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idutilisateur,mois,idFraisForfait,quantite) 
			values('$idutilisateur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un utilisateur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idutilisateur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idutilisateur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un utilisateur a une fiche de frais
 
 * @param $idutilisateur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idutilisateur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idutilisateur ='$idutilisateur'  
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
 /**
 * Retourne les informations d'une fiche de frais d'un utilisateur pour un mois donné
 
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/
        public function getLesMoisEnAttente(){
            $req = "select fichefrais.mois as mois"
                    . " from fichefrais"
                    . " where fichefrais.idEtat = 'CL'"
                    . " order by fichefrais.mois DESC ";
            $res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
        
        public function getLesVisiteurs($unMois){
		$req = "SELECT utilisateur.id,utilisateur.nom, utilisateur.prenom  FROM fichefrais, utilisateur WHERE fichefrais.idutilisateur = utilisateur.id AND fichefrais.idEtat = 'CL' AND mois=".$unMois."";
		$res = PdoGsb::$monPdo->query($req);
		$lesVisiteurs =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
                    $id = $laLigne['id'];
			$nom = $laLigne['nom'];
			$prenom = $laLigne['prenom'];
			$lesVisiteurs["$nom"]=array(
                     "id"=>"$id",
		     "nom"=>"$nom",
		     "prenom"  => "$prenom",
			
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesVisiteurs;
	}
        
	
	public function getLesInfosFicheFrais($idutilisateur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idutilisateur ='$idutilisateur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idutilisateur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idutilisateur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idutilisateur ='$idutilisateur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}
        function reporterFraisHorsForfait($idFrais, $dernierMois)
        {
             //requete pour mettre a jour le champs
            $req = "update lignefraishorsforfait set mois = $dernierMois where id = $idFrais";

            //execution de la requete
            PdoGsb::$monPdo->exec($req);
          
        }
        static public function getLesVisiteursSuivi()
        {
            $req="SELECT * FROM utilisateur where comptable=0 order by nom";
            $sql = PDOGsb::$monPdo->query($req);
            $lesVisiteurs= $sql->fetchAll();
            return ($lesVisiteurs);
        }
        static public function getLesMoisSuivi()
        {
            $req='select distinct mois from Fichefrais';
            $sql= PdoGsb::$monPdo->query($req);
            $listeMois= $sql->fetchAll();
            return ($listeMois);
            
        }
        static public function verifExistanceFiche($idutilisateur, $mois){
            $req="select * from fichefrais where fichefrais.idutilisateur='$idutilisateur' and fichefrais.mois ='$mois' and idEtat='VA'";
            $sql=PdoGsb::$monPdo->query($req); 
            $listeFiches= $sql->fetchAll();
            return ($listeFiches);
        }
        static public function getLesFraisForfaitSuivi($idutilisateur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite , (lignefraisforfait.quantite*fraisforfait.montant) as montant from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idutilisateur ='$idutilisateur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
        function creerPdfReservation($idutilisateur, $mois){
        $totalFF=0;
        $totalHF=0;
        $total=0;
        $numAnnee =substr($mois,0,4);
        $numMois =substr($mois,4,2);
        $moisEtAnnee = ''.$numMois.'/'.$numAnnee.'';
        $req1="select * from utilisateur where id='$idutilisateur'";
        $sql1=PdoGsb::$monPdo->query($req1);
        $tabVisiteur= $sql1->fetch();
        $reqFraisForfait="select fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite, fraisforfait.montant as montantunitaire , (lignefraisforfait.quantite*fraisforfait.montant) as montant from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idutilisateur ='$idutilisateur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";
        $resFraisForfait=PdoGsb::$monPdo->query($reqFraisForfait);
	$tabFraisForfait = $resFraisForfait->fetchAll();
        $reqFraisHF ="select lignefraishorsforfait.libelle as libelle, lignefraishorsforfait.date as date, lignefraishorsforfait.montant as montant from lignefraishorsforfait where lignefraishorsforfait.idutilisateur ='$idutilisateur' 
		and lignefraishorsforfait.mois = '$mois'";	
	$resFraisHF = PdoGsb::$monPdo->query($reqFraisHF);
	$tabFraisHF = $resFraisHF->fetchAll();
        
        $pdf=ob_get_clean();    
        $pdf=new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        // $pdf->Image('images/logo.jpg',20,20, 64, 48);
        $pdf->SetTextColor(51,102,255);
        $pdf->Cell(180,10,utf8_decode("Remboursement des Frais engagés"),0,0,'C');
        $pdf->Ln(50);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(40,10,'Visiteur : '.$tabVisiteur['nom']." ".$tabVisiteur['prenom'],'C');
        $pdf->Cell(20);
        $pdf->Ln(10);
        $pdf->Cell(1,10,'ID : '.$tabVisiteur['id'],'C');
        $pdf->Cell(20);
        $pdf->Ln(10);
        $pdf->Cell(40,10,'Mois : '.$moisEtAnnee,'C');
        $pdf->Ln(30);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(75);
        $pdf->Cell(10,10,utf8_decode('Frais Basiques'),'C');
        $pdf->SetFont('Arial','I',12);
        $pdf->SetTextColor(150,102,255);
        $pdf->Ln(20);
        $pdf->Cell(20,10,utf8_decode('Frais forfétaires'),'C');
        $pdf->Cell(15);        
        $pdf->Cell(20,10,utf8_decode('Quantité'),'C');
        $pdf->Cell(25,10,'Montant unitaire','C');
        $pdf->Cell(10);   
        $pdf->Cell(20,10,'Total','C');
        $pdf->SetTextColor(0,0,0);
        foreach($tabFraisForfait as $unForfait){   
        $pdf->Ln(10);
        $pdf->Cell(45, 10, utf8_decode($unForfait['libelle']));
        $pdf->Cell(20, 10, utf8_decode($unForfait['quantite']));
        $pdf->Cell(20, 10, utf8_decode($unForfait['montantunitaire']));
        $pdf->Cell(20, 10, utf8_decode($unForfait['montant']));
        $totalFF=$totalFF+$unForfait['montant'];
        }
        $pdf->Ln(40);
        $pdf->Cell(80);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(10,10,utf8_decode('Autres Frais'),'C');
        $pdf->SetFont('Arial','I',12);
        $pdf->SetTextColor(150,102,255);
        $pdf->Ln(20);
        $pdf->Cell(20,10,utf8_decode('Date'),'C');
        $pdf->Cell(15);        
        $pdf->Cell(20,10,utf8_decode('Libellé'),'C');
        $pdf->Cell(25); 
        $pdf->Cell(45,10,'Montant','C');
        $pdf->SetTextColor(0,0,0);
        foreach($tabFraisHF as $unForfaitHF){   
        $pdf->Ln(10);
        $pdf->Cell(35, 10, utf8_decode($unForfaitHF['date']));
        $pdf->Cell(20, 10, utf8_decode($unForfaitHF['libelle']));
        $pdf->Cell(25); 
        $pdf->Cell(35, 10, utf8_decode($unForfaitHF['montant']));
        $totalHF=$totalHF+$unForfaitHF['montant'];
        }
        $pdf->Ln(20);
        $total=$totalFF+$totalHF;
        $pdf->Cell(120);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(35, 10, utf8_decode('Total : '.$total.' euros'));
        
        $pdf->Output();
        }
        
}
?>