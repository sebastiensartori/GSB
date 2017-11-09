<div id="contenu">
    <h2>Modification de la fiche de frais</h2>
    <div class="encadre">
    <!-- formulaire pour recuperer les quantites -->
    <form method="POST"  action="index.php?uc=validerFrais&action=validerModif">
        <!--unMois et idVisiteur -->
        <input type="hidden" value="<?php echo $moisASelectionner?>" name="unMois" />
        <input type="hidden" value="<?php echo $visiteurASelectionner?>" name="idVisiteur" />
        
        
            
         <?php
                foreach ($lesFraisForfait as $FraisForfait)
                {
                    $idFrais = $FraisForfait['idfrais'];
                    $libelle = $FraisForfait['libelle'];
                    $quantiteForfait = $FraisForfait['quantite'];
	?>
              
        <strong><label for="idFrais"><?php echo $libelle ?></label></strong>
        <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais?>]" value="<?php echo $quantiteForfait?>" ></br>
		
	<?php
		}
	?>
       
        </div>         
                <input id="confirmer" type="submit"  value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
                
    </form>
