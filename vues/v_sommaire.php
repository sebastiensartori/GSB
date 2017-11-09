    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
				  Comptable :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
			</li>
            <li class="smenu">
              <a href="index.php?uc=gererPaiement&action=selectionnerVisiteurs" title="Saisie fiche de frais ">Suivie Paiement</a>
           </li> 
           <li class="smenu">
              <a href="index.php?uc=validerFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Fiches de frais</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        
    </div>
    