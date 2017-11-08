    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
				  <br><br>Visiteur :<br><br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom'] ?>
			</li>
                        <br>
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Afficher les mois">Validation des frais</a>
           </li><br>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Suivre le paiement des frais</a>
           </li><br>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        
    </div>
    