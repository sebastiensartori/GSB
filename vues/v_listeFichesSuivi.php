<center>
<?php if(!empty($listeFiches)){ ?>
<h1> Fiche de frais </h1>
<table border="1">
<thead> 
<th>IdVisiteur</th><th>Mois</th><th>nbJustificatifs</th><th>Montant Valide</th><th>Date Modif</th><th>Etat Fiche</th><th>PDF</th>
</thead>
    
<?php
if (is_array($listeFiches)){
foreach ($listeFiches as $uneFiche){
echo "<tr><td>".$uneFiche['idutilisateur']."</td>
                 <td>".$uneFiche['mois']."</td>
                 <td>".$uneFiche['nbJustificatifs']."</td>
                 <td>".$uneFiche['montantValide']."</td>
                 <td>".$uneFiche['dateModif']."</td>
                 <td>".$uneFiche['idEtat']."</td>
                 <td><a href='index.php?uc=pdf&action=voirPDF&id=".$uneFiche['idutilisateur']."&mois=".$uneFiche['mois']."'> <img src='images/pdf.jpg' style='width:50px;'></a></td></tr>";
}}
else{ echo "non pas un tableau";}
    echo "</table>";   
?>   
<h1> Frais forfaitaires </h1>

<table border="1">
    <thead> 
    <th>IdFrais</th><th>Libelle</th> <th>Quantite</th><th>Montant</th><th>Total</th>
    </thead>
    
<?php
$totalFraisForfait=0;
$total=0;
foreach ($tabFrais as $unFraisForfait){
    $total=$unFraisForfait['montant']+$total;
    
   echo "<tr><td>".$unFraisForfait['idfrais']."</td>
                 <td>".$unFraisForfait['libelle']."</td>
                 <td>".$unFraisForfait['quantite']."</td>
                 <td>".$unFraisForfait['montant']."  €</td>
                 <td>".$total."€</td></tr>";
                 $totalFraisForfait=$total+$totalFraisForfait;
}
$totalzer=$total;
?>
    
</table>

<!-- FRAIIIIS HORS FORFAIT -->
<h1> Frais hors forfait </h1>

<table border="1">
    <thead> 
    <th>Date</th><th>Libelle</th> <th>Montant</th>
    </thead>
    
<?php
$totalFraisHF=0;
foreach ($tabHorsForf as $unFraisHF){
   echo "<tr><td>".$unFraisHF['date']."</td>
                 <td>".$unFraisHF['libelle']."</td>
                 <td>".$unFraisHF['montant']."  €</td></tr>";
                 $totalFraisHF=$unFraisHF['montant']+$totalFraisHF;
}
echo "</table>
<h1> Total des frais : "; 
echo $totalzer+$totalFraisHF;
echo "€</h1>"; 
echo '<form action="index.php?uc=gererPaiement&action=validerPaiement" method="POST">
    <input type="submit" value="Mettre en paiement">';
    $id=$uneFiche['idutilisateur'];
    $mois=$uneFiche['mois'];?> 
   <input type="hidden" name="id" value="<?php echo $id ?>">
   <input type="hidden" name="mois" value="<?php echo $mois ?>"> <?php 
echo "</div>";
"</form></center>'";}
else
{
echo "Pas de fiche de frais pour ce visiteur ce mois.";
}
?> 