<center><form action='index.php?uc=gererPaiement&action=listeFichesSuivi' method='POST'>
<select name='idVisiteur' value="nomvisiteur" class='zone'>
    <option value=''> Choisir un Visiteur </option>
<?php
foreach ($lesVisiteurs as $visiteurs){
        echo "<option value=".$visiteurs['id'].">".$visiteurs['nom'].' '.$visiteurs['prenom']."</option>";
      }
?> 
</select> 
<select name='Mois' value='moisvisiteur' class='zone'>
    <option value=''> Choisir un Mois </option>
<?php
foreach ($lesMois as $mois){
    $a = substr($mois['mois'],0,4);
    $m = substr($mois['mois'],4,2);
    echo $a;
    echo "<option value=".$mois['mois'].">".$m."/".$a."</option>";
}
?>
</select>
        <input type='submit' name="valider"/>
</form>
</center>