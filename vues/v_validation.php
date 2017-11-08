<h3>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : </h3>

<div class="encadre">
    <p>Etat : <?php echo $libEtat ?> depuis le <?php echo $dateModif?> <br></p>
        
        
  	<table class="listeLegere">
  	    <caption>Frais Forfaits </caption>
            
            <tr>
        <?php
            foreach ( $lesFraisForfait as $unFraisForfait ) 
            {
                $libelle = $unFraisForfait['libelle'];
        ?>	
                <th> <?php echo $libelle?></th>
        <?php } ?>
            </tr>
            <tr>
        <?php
            foreach (  $lesFraisForfait as $unFraisForfait  ) 
            {
                $quantite = $unFraisForfait['quantite'];
	?>
                <td class="qteForfait"><?php echo $quantite?> </td>
	<?php } ?>
        </tr>
        
        </table>
    
       
        <form action="index.php?uc=validerFrais&action=modifier" method="post">
            <input type="hidden" name="idVisiteur" value="<?php echo $visiteurASelectionner ?>" />
            <input type="hidden" name="mois" value="<?php echo $moisASelectionner ?>" />
            <input type="submit" value="Modifier" />
        </form>
        
        
        
  	<table class="listeLegere">
            <caption>Frais Hors Forfaits </caption>
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>
                <th>Reporter</th> 
                <th>Supprimer</th> 
            </tr>
        <?php      
            foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
            {
                    $idFrais = $unFraisHorsForfait['id'];
                    $date = $unFraisHorsForfait['date'];
                    $libelle = $unFraisHorsForfait['libelle'];
                    $montant = $unFraisHorsForfait['montant'];
        ?>
            
            <tr>
                <td><?php echo $date ?></td>
                
                <td><?php echo $libelle ?></td>
                
                <td><?php echo $montant ?></td>
                
                
                
                <form action="index.php?uc=actionFicheFrais&action=reporter" method="post">
                <input type="hidden" name="hdIdFraisHorsForfait" value="<?php echo $idFrais ?>" />
                <td><input type="submit" value="Reporter" /></td>
            </form>

            
            <form action="index.php?uc=actionFicheFrais&action=refuser" method="post">
                <input type="hidden" name="hdIdFraisHorsForfait" value="<?php echo $idFrais ?>" />
                <td>
                    <input type="submit" value="Refuser" />
                </td>
            </form>
               
            
            </tr>
        <?php } ?>
        </table>
        
        
        <form action="index.php?uc=validerFrais&action=validerFiche" method="post">
            <input type="hidden" name="idVisiteur" value="
                <?php echo $visiteurASelectionner ?>" />
            <input type="hidden" name="mois" value="
                <?php echo $moisASelectionner ?>" />
            <input type="submit" value="Valider la fiche" />
        </form>
        
    </div>
</div>