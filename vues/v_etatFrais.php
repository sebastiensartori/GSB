<h3>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : </h3>

<div class="encadre">
    <p class="p">Etat : <?php echo $libEtat ?> depuis le <?php echo $dateModif?> <br></p>
        
        <!-- tableau des frais forfait -->
  	<table class="listeLegere">
            <caption class="caption">Eléments forfaitisés </caption>
            <!-- entete du tableau -->
            <tr>
        <?php
                foreach ( $lesFraisForfait as $FraisForfait ) 
                {
                    $libelle = $FraisForfait['libelle'];
        ?>	
                    <th> <?php echo $libelle?></th>
                   
        <?php
                }
        ?>
                    <th>Modifier</th>
            </tr>
           
        
            <tr>
        <?php
                foreach (  $lesFraisForfait as $FraisForfait  ) 
                {
                    $quantiteForfait = $FraisForfait['quantite'];
	?>
                    <td class="qteForfait"><?php echo $quantiteForfait?> </td>
	<?php
                }
	?>
            
        
        <meta http-equiv="refresh" content="5000">
    
        <!-- formulaire pour modification des elements -->
        <form action="index.php?uc=validerFrais&action=modifier" method="POST">
            <input type="hidden" name="idVisiteur" value="<?php echo $visiteurASelectionner ?>" />
            <input type="hidden" name="unMois" value="<?php echo $moisASelectionner ?>" />
            <td><input type="submit" value="Modifier" /></td>
            
        </form>
        </tr>
        </table>
        
        <!-- tableau des frais hors forfait -->
  	<table class="listeLegere">
            <caption class="caption">Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -</caption>
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>
                <th>Reporter frais</th> 
                <th>Supprimer frais</th> 
             
            </tr>
        <?php      
            foreach ( $lesFraisHorsForfait as $FraisHorsForfait ) 
            {
                    $idFrais = $FraisHorsForfait['id'];
                    $date = $FraisHorsForfait['date'];
                    $libelle = $FraisHorsForfait['libelle'];
                    $montant = $FraisHorsForfait['montant'];
          ?>          
                    
                
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                
                <!-- formulaire pour recuperer les infos -->
                <form action="index.php?uc=validerFrais&action=reporter" method="POST">
                    <input type="hidden" name="idFraisHorsForfait" value="<?php echo $idFrais ?>" />
                    <input type="hidden" name="idVisiteur" value="<?php echo $visiteurASelectionner ?>" />
                    <input type="hidden" name="unMois" value="<?php echo $moisASelectionner ?>" />
                    <input type="hidden" name="nom" value="<?php echo $nom ?>"/>
                    <input type="hidden" name="prenom" value="<?php echo $prenom ?>"/>
                    <td><input type="submit" value="Reporter" /></td>
                </form>
            
            
                <!-- formulaire pour recuperer les infos -->
                <form action="index.php?uc=validerFrais&action=supprimer" method="POST">
                    <input type="hidden" name="idFraisHorsForfait" value="<?php echo $idFrais ?>" />
                    <input type="hidden" name="idVisiteur" value="<?php echo $visiteurASelectionner ?>" />
                    <input type="hidden" name="unMois" value="<?php echo $moisASelectionner ?>" />
                    <input type="hidden" name="nom" value="<?php echo $nom ?>"/>
                    <input type="hidden" name="prenom" value="<?php echo $prenom ?>"/>
                    <td><input type="submit" value="Refuser" /></td>
</form>
            <?php
            }
	?>
        </table>
        
        
        <!-- formulaire pour recuperer les infos -->
        <form action="index.php?uc=validerFrais&action=validerFicheFrais" method="post">
            <input type="hidden" name="idVisiteur" value="<?php echo $visiteurASelectionner ?>" />
            <input type="hidden" name="unMois" value="<?php echo $moisASelectionner ?>" />
            <input type="hidden" name="nom" value="<?php echo $nom ?>"/>
            <input type="hidden" name="prenom" value="<?php echo $prenom ?>"/>
            <input type="submit" value="Valider" />
        </form> 
    </div>
</div>