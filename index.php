<html>
    <head>
        <title>
            Qui Paie Quoi ?
        </title>
    <link href="style.css" rel="stylesheet" media="all" type="text/css"> 
    <script type="text/javascript">

/*
 * Qui paie quoi ?
 *
 * Calcule ce que doivent les personnes lors d'achat en commun de plusieurs services
 * 
 * @auteur le sanglier des ardennes <lesanglierdesardennes@gmail.com>
 * @date  juin 2012
 * @version 0.0.2
 * 
 */

/* Sélectionne l'option du select */
function selectAvec(numcommande) {
 var selectavec=document.getElementById("selectAvec_" + numcommande);
 document.getElementById("commandes_"+numcommande+"_avec").value = selectavec.options[selectavec.selectedIndex].value;
}

// Calcule des services à commander 
function calculerAcommander(numcommande){
    // Nombre de commande
    var nbcommande = document.getElementById("numcommande").value;

    // Tableau des services
    var services = new Array();

    for(i = 1; i <= nbcommande; i++){
        service = document.getElementById("commandes_"+i+"_service").value;
        if(service != ""){
            services[service] = services[service] + 1;
        }

        // Clé non-enregistré donc 1er clé
        if(isNaN(services[service])){
            services[service] = 1;
        }
    }

    // Boucle les services à commander 
    var acommander;
    for(var cle in services){
        if(cle != ""){ // Bug : Dans le tableau des services,il y a la clé "chaine vide"
            if(services[cle] > 1){
                acommander += services[cle] + " " + cle + "s\n";
            }else{
                acommander += services[cle] + " " + cle + "\n";
            }                        
        }
    }

    // Bug : "Undefined" est dans le tableau
    // Supprime le typeof "Undefined"
    acommander = acommander.substring(9,acommander.length);

    // Afficher les services à commander
    document.getElementById("acommander").value = acommander;
}

function calculerPartAPayer(numcommande){
    numcommande = numcommande + 1;

    // Valeur "Courante"
    var avecCourant = document.getElementById("commandes_"+numcommande+"_avec").value;
    var serviceCourant = document.getElementById("commandes_"+numcommande+"_service").value;
    var tarifCourant = parseFloat(document.getElementById("commandes_"+numcommande+"_tarif").value);
    var paiementCourant = parseFloat(document.getElementById("commandes_"+numcommande+"_paiement").value);

    // Nombre total de commande
    var nbcommande = document.getElementById("numcommande").value;

    // Nombre de personne "avec"
    var nbAvec = 1;

    // Variables de calcule
    var nom  = "";
    var avec = "";
    var service = "";
    var reglement = 0;

    if(avecCourant != ""){ // La personne commande avec quelqu'un
        // Chercher ce quelqu'un
        for(i = 1; i <= nbcommande; i++){
            nom = document.getElementById("commandes_"+i+"_nom").value;   
            avec = document.getElementById("commandes_"+i+"_avec").value;
            service = document.getElementById("commandes_"+i+"_service").value;
            tarif =  parseFloat(document.getElementById("commandes_"+i+"_tarif").value);
            paiement = parseFloat(document.getElementById("commandes_"+i+"_paiement").value);

            // Nombre de personne qui sont lié "avec"
            if(avecCourant == avec){
                // Partage le même service
                if(service == ""){
                    nbAvec++;
                }
            }
            // Récupérer le réglement qui correspond à la personne "avec"
            if(avecCourant == nom){
                if(avec == ""){
                    reglement = tarif;
                }
            }
        } // Fin : for

        // Montant à payer à la personne pour chaque "avec"
        for(i = 1; i <= nbcommande; i++){
            var avec = document.getElementById("commandes_"+i+"_avec").value;
            var tarif = parseFloat(document.getElementById("commandes_"+i+"_tarif").value);
            var paiement = parseFloat(document.getElementById("commandes_"+i+"_paiement").value);
            if(avecCourant == avec){
                document.getElementById("commandes_"+i+"_apayer").value = reglement / nbAvec;
                // La "personne" courante se fait payer un "service" par "avec"
                if(tarif != 0 && paiement == 0){
                    document.getElementById("commandes_"+i+"_apayer").value = tarif;
                    document.getElementById("commandes_"+i+"_arendre").value = 0;
                }
            }
        } // Fin : for
    } // Fin : if
}

// Calcule de tous les cellules du tableau du formulaire
function calculerCellule(numcommande) { // Commande courante
    // Nombre de commande
    var nbcommande = document.getElementById("numcommande").value;

    // Calcule "arendre"
    var tarif = parseFloat(document.getElementById("commandes_"+numcommande+"_tarif").value);
    var paiement = parseFloat(document.getElementById("commandes_"+numcommande+"_paiement").value);

    // Calcul de la commande la plus élevé et de son numéro
    var tarifTotalCommande = 0;
    var paiementEleve = 0;
    var numCommandePaiementEleve = 0;

    // Parcours toutes les commandes
    for(i = 1; i <= nbcommande; i++){
        var numCommandePaiementEleve;
        // La même personne a commandé quelque chose
        var valeurNomBoucle = document.getElementById("commandes_"+i+"_nom").value;
        var valeurNomActuel =  document.getElementById("commandes_"+numcommande+"_nom").value;
        if(valeurNomBoucle == valeurNomActuel){ 
            // Cumul des tarifs des commandes de la même personne                 
            tarifTotalCommande += parseFloat(document.getElementById("commandes_"+i+"_tarif").value);

            // Récupérer le paiment de la commande courant
            paiementCourant = parseFloat(document.getElementById("commandes_"+i+"_paiement").value );
            if(isNaN(paiementCourant)){
                paiementCourant = 0;
            }
            // La personne ne paie pas donc la commande fait partie d'une autre commande
            if(paiementCourant == 0) {
                document.getElementById("commandes_"+i+"_arendre").value  = 0;
            }else{  
                // La personne paie et rend de la monnaie
                var tarif = parseFloat(document.getElementById("commandes_"+i+"_tarif").value);
                var paiement = parseFloat(document.getElementById("commandes_"+i+"_paiement").value);
                document.getElementById("commandes_"+i+"_arendre").value  = paiement-tarif;
            }

            // Recherche le paiement le plus élevé : valeur et numcommande
            if(paiementCourant > paiementEleve){
                numCommandePaiementEleve = i; 
                paiementEleve = parseFloat(document.getElementById("commandes_"+i+"_paiement").value) ;   
            }
        } // Fin if : Test valeurNomBoucle == valeurNomActuel
    } // Fin for : Boucle de toutes les commandes

    // Recupérer le numéro de la commande la plus élévé
    if(numCommandePaiementEleve == 0){
        numCommandePaiementEleve = numcommande;
    }
    
    // Calcul de la valeur de la commande élevé et la placer dans la bonne commande
    
    //document.getElementById("commandes_"+numCommandePaiementEleve+"_arendre").value = paiementEleve - tarifTotalCommande;

    // Calcul des colonnes 
    // Calcul de la colonne : tarif
    var totaltarif = 0;
    for(i=1;i<=nbcommande;i++){
        totaltarif += parseFloat(document.getElementById("commandes_"+i+"_tarif").value);
    } 
    document.getElementById("totaltarif").value = totaltarif;
    // Calcul de la colonne : paiement
    var totalpaiement = 0;
    for(i=1;i<=nbcommande;i++){
        totalpaiement += parseFloat(document.getElementById("commandes_"+i+"_paiement").value);
    } 
    document.getElementById("totalpaiement").value = totalpaiement;

    // Calcul de la colonne : arendre
    var totalarendre = 0;
    for(i=1;i<=nbcommande;i++){
        totalarendre += parseFloat(document.getElementById("commandes_"+i+"_arendre").value);
    } 
    document.getElementById("totalarendre").value = totalarendre;
}
        </script>
    </head>
<body>
<script language="javascript">
<?php 
    // Affiche l'écran d'accueil seulement à la 1er connexion
    if(!isset($_POST["action"])){ 
        $activeAccueil = false;
        if($activeAccueil){
?>
            alert("Qui paie quoi ?\nVersion 0.0.2\n\nPar le Sanglier des Ardennes\n\nRemarques/commentaires/insultes/encouragements :\n\nle sanglier des ardennes (a) gmail (.) com\n");
<?php 
        }
    } 
?>
</script>
<?php

// Déactive le report des erreurs sous l'environnement Lampp 1.7.2
error_reporting(0);

// Génére la liste des parts
function genererListePart($numcommande){
    // Générer le select
    $select  ="<select id=\"commandes_".$numcommande."_part\" name=\"commandes[".$numcommande."][part]\" width=\"115px\" style=\"width: 115px\" readonly>";
    $select .= "  <option value=\"1\" default>1</option>";
    $select .= "  <option value=\"2\" >1/2</option>";
    $select .= "  <option value=\"3\" >1/3</option>";
    $select .= "  <option value=\"4\" >1/4</option>";
    $select .= "  <option value=\"5\" >2/3</option>";
    $select .= "  <option value=\"6\" >3/4</option>";
    $select .= "</select>";
    
    return $select;
}

// Récupérer les valeurs du formulaire posté
if(isset($_POST["action"]) && $_POST["action"] == "Ajouter" && isset($_POST['commandes'])){
    // Initialisation du compte
    $compte = $_POST['compte'];
    $tableauCommande = count($_POST['commandes']);
    $numcommande = $_POST['numcommande'];
    $numcommandeactuel = $numcommande + 1;
    for($numcommande=1;$numcommande < $tableauCommande+1;$numcommande++){
        $commandes[$numcommande]['nom'] = $_POST['commandes'][$numcommande]['nom'];
        $commandes[$numcommande]['avec'] = $_POST['commandes'][$numcommande]['avec'];
        $commandes[$numcommande]['service'] = $_POST['commandes'][$numcommande]['service'];
        $commandes[$numcommande]['tarif'] = $_POST['commandes'][$numcommande]['tarif'];
        $commandes[$numcommande]['paiement'] = $_POST['commandes'][$numcommande]['paiement'];
        $commandes[$numcommande]['apayer'] = $_POST['commandes'][$numcommande]['apayer'];
        $commandes[$numcommande]['part'] = $_POST['commandes'][$numcommande]['part'];
        $commandes[$numcommande]['arendre'] = $_POST['commandes'][$numcommande]['arendre'];
    }
    $acommander = $_POST['acommander'];
}else{
    $numcommandeactuel= 1;
    $compte = "";
    $acommander = "";
}

// Initialisation de la date
$date = date("j/n/y");
?>
<h1>Qui Paie Quoi ?</h1>
<form name="form" action="index.php" method="post">
Compte : <input type="text" value="<?php echo $compte; ?>" id="compte" name="compte">&nbsp;Date : <?php echo $date; ?> 
<hr/>
<input type="hidden" value="<?php echo $numcommandeactuel; ?>" name="numcommande" size="3" id="numcommande">
<input type="hidden" value="<?php echo $compte; ?>" name="compte"  id="compte">
<input type="hidden" value="<?php echo $date; ?>" name="date"  id="date">

<table>
    <tr>
        <td>
            &nbsp;
        </td>
        <td>
            Nom
        </td>
        <td>
            Avec
        </td>
        <td>
            Service 
        </td>
        <td>
            Tarif
        </td>
        <td>
            Paiement
        </td>
        <td>
            Part &agrave; payer
        </td>        
        <td style="display:none;">
            Part
        </td>
        <td>
            A rendre
        </td>
        <td>
            &nbsp;
        </td>
    </tr>           

<?php

if(isset($_POST["action"]) && $_POST["action"] == "Ajouter" && isset($_POST['commandes'])){
    for($numcommande=1;$numcommande<$tableauCommande+1;$numcommande++){
        ?>
        <tr>
            <td>
                <?php echo $numcommande; ?>
            </td>
            <td>
                <input type="text" size="10" title="Veuillez tapez le nom de la personne en minuscule" id="commandes_<?php echo $numcommande; ?>_nom" name="commandes[<?php echo $numcommande; ?>][nom]" value="<?php echo $commandes[$numcommande]['nom']; ?>">
            </td>
            <td>  
                 <input type="text" size="10" title="Veuillez tapez le nom de la personne en minuscule" id="commandes_<?php echo $numcommande; ?>_avec" name="commandes[<?php echo $numcommande; ?>][avec]" value="<?php echo $commandes[$numcommande]['avec']; ?>" style="width:150px;">
            </td>
            <td>
                <input type="text" size="10" title="Veuillez tapez le nom du service en minuscule" id="commandes_<?php echo $numcommande; ?>_service" name="commandes[<?php echo $numcommande; ?>][service]" value="<?php echo $commandes[$numcommande]['service']; ?>">
            </td>
            <td>
                <input type="text" size="10" id="commandes_<?php echo $numcommande; ?>_tarif" name="commandes[<?php echo $numcommande; ?>][tarif]" value="<?php echo $commandes[$numcommande]['tarif']; ?>" onChange="calculerCellule(<?php echo $numcommande; ?>)">&nbsp;&euro;
           </td>
            <td>
                <input type="text" size="10" id="commandes_<?php echo $numcommande; ?>_paiement" name="commandes[<?php echo $numcommande; ?>][paiement]" value="<?php echo $commandes[$numcommande]['paiement']; ?>" onChange="calculerCellule(<?php echo $numcommande; ?>);calculerAcommander(<?php echo $numcommandeactuel; ?>);calculerPartAPayer(<?php echo $numcommande; ?>)">&nbsp;&euro;
            </td>
            <td>
                <input type="text" size="10" id="commandes_<?php echo $numcommande; ?>_apayer" name="commandes[<?php echo $numcommande; ?>][apayer]" value="<?php echo $commandes[$numcommande]['apayer']; ?>" readonly>&nbsp;&euro;
            </td>
            <td style="display:none;">
                <?php
                   echo  genererListePart($numcommande);
                ?>
            </td>
            <td>
                <input type="text" size="10" id="commandes_<?php echo $numcommande; ?>_arendre" name="commandes[<?php echo $numcommande; ?>][arendre]" value="<?php echo $commandes[$numcommande]['arendre']; ?>" readonly>&nbsp;&euro;
            </td>
            <td>
                &nbsp;
            </td>
        </tr>    
        <?php
    } 
} 
?>
    <tr>
        <td>
            <?php echo $numcommandeactuel; ?>
        </td>
        <td>
            <input type="text" size="10" title="Veuillez tapez le nom de la personne en minuscule" id="commandes_<?php echo $numcommandeactuel; ?>_nom" name="commandes[<?php echo $numcommandeactuel; ?>][nom]" value="<?php echo $commandes[$numcommandeactuel]['nom']; ?>">
        </td>
        <td>
			<?php
                // Marge du haut du premier input
                $marge = 152;

                // Espacement entre chaque input
                $espacement = 29;

                if($numcommandeactuel == 1){
                    // Margin du haut de l'input
                    $positionHaut = $marge;
                }else{
                    $positionHaut = $marge + (($numcommandeactuel-1) * $espacement);
                }
			?>
            <div style="top: <?php echo $positionHaut; ?>px;width: 190px">
                <select style="width: 175px;height:25px" id="selectAvec_<?php echo $numcommandeactuel; ?>" name="selectAvec_<?php echo $numcommandeactuel; ?>" onChange="selectAvec(<?php echo $numcommandeactuel; ?>);">
                    <option value=""></option>
                    <?php
                        // Génération de la liste des noms
                        $nbcommande = $_POST['numcommande'];
                        // Tableau temporaire
                        for($numcommande=1;$numcommande <= $nbcommande;$numcommande++){
                            // Le nom n'existe pas donc l'ajouter
                            $temp[] = $_POST['commandes'][$numcommande]['nom'];
                        }
                        // Supprime les doublons
                        $temp = array_unique($temp);
                        // Génére les options du select
                        for($numcommande=0;$numcommande < count($temp);$numcommande++){
                            echo "<option value=\"" . $temp[$numcommande] . "\">" . $temp[$numcommande] . "</option>";
                        }
                    ?>
                </select>
            </div>
			<div style="z-index:2;width:150px;position: absolute;top: <?php echo $positionHaut; ?>px;">
	            <input type="text" size="10" title="Veuillez tapez le nom de la personne en minuscule" id="commandes_<?php echo $numcommandeactuel; ?>_avec" name="commandes[<?php echo $numcommandeactuel; ?>][avec]" value="<?php echo $commandes[$numcommandeactuel]['avec']; ?>" style="width: 150px;position: absolute;top: 0px;">
			</div>
            <!--
			<iframe style="z-index:1;width:175px;height:17px;position: absolute;top: <?php echo $positionHaut; ?>px;left: 137px;" frameborder="0" >
			</iframe>
            -->
        </td>
        <td>
            <input type="text" size="10" title="Veuillez tapez le nom du service en minuscule" id="commandes_<?php echo $numcommandeactuel; ?>_service" name="commandes[<?php echo $numcommandeactuel; ?>][service]" value="<?php echo $commandes[$numcommandeactuel]['service']; ?>">
        </td>
        <td>
            <input type="text" size="10" id="commandes_<?php echo $numcommandeactuel; ?>_tarif" name="commandes[<?php echo $numcommandeactuel; ?>][tarif]" value="<?php echo $commandes[$numcommandeactuel]['tarif']; ?>" onChange="calculerCellule(<?php echo $numcommandeactuel; ?>)">&nbsp;&euro;
        </td>
        <td>
            <input type="text" size="10" id="commandes_<?php echo $numcommandeactuel; ?>_paiement" name="commandes[<?php echo $numcommandeactuel; ?>][paiement]" value="<?php echo $commandes[$numcommandeactuel]['paiement']; ?>" onChange="calculerCellule(<?php echo$numcommandeactuel; ?>);calculerAcommander(<?php echo $numcommandeactuel; ?>);calculerPartAPayer(<?php echo $numcommande; ?>);">&nbsp;&euro;
        </td>
        <td>
            <input type="text" size="10" id="commandes_<?php echo $numcommandeactuel; ?>_apayer" name="commandes[<?php echo $numcommandeactuel; ?>][apayer]" value="<?php echo $commandes[$numcommandeactuel]['apayer']; ?>" readonly>&nbsp;&euro;
        </td>
        <td style="display:none;">
            <?php
                echo genererListePart($numcommandeactuel);
            ?>
        </td>
        <td>
            <input type="text" size="10" id="commandes_<?php echo $numcommandeactuel; ?>_arendre" name="commandes[<?php echo $numcommandeactuel; ?>][arendre]" value="<?php echo $commandes[$numcommandeactuel]['arendre']; ?>" readonly>&nbsp;&euro;   
        </td>
        <td>
            <input type="submit" value="Ajouter" name="action">
        </td>
    </tr> 
    <tr>
        <td>
            &nbsp;
        </td>        
        <td>
            &nbsp;
        </td>
        <td> 
            &nbsp;
        </td>
        <td align="right">
            Total : 
        </td>
        <td>
            <input type="text" size="10" name="totaltarif" id="totaltarif" readonly>&nbsp;&euro;
        </td>
        <td>
            <input type="text" size="10" name="totalpaiement" id="totalpaiement" readonly>&nbsp;&euro;
        </td>
        <td>
            &nbsp;
        </td>        
        <td style="display:none;">

        </td>
        <td>
            <input type="text" size="10" name="totalarendre" id="totalarendre" readonly>&nbsp;&euro;
        </td> 
        <td>
            &nbsp;
        </td>                
    </tr>
</table>
<hr/>
A commander: <br/><br/>
<input type="submit" value="Envoyer" name="action" ><br/><br/>
<textarea name="acommander" id="acommander" rows="10" cols="20" style="font-size: 200%;" readonly><?php echo $acommander; ?></textarea>
</form>            
</body>
</html>
