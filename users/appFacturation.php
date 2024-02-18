<?php

require_once 'init.php';

require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

date_default_timezone_set('UTC');
$dateNow = date('Y-m-d');

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
    function dateValid() {
        var semaineVente=document.getElementById('semaineVente').value;
        var lieuVente=document.getElementById('lieuVente').value;

        if (semaineVente && lieuVente) {
            window.location.href = "https://groupecreato.online/users/appFacturation.php?semaineVente="+semaineVente+"&X_lieuVente="+lieuVente;
        }
        else { alert('Veuillez selectionner une Date et un Lieu de vente !'); }
    }
</script>

<style>
.bg-light {
    background-color: #dadada!important;
}
</style>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<div class="row">

	<div class="col-md-12">

	    <br>

	    <h3 >FACTURATION & RÉPARTITION</h3>

	    <hr>

	</div>

</div>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<div class="row">

    <div class="col-md-4 mb-3">

            <div class="card border border-secondary">    <!-- Card debut  -->

                <div class="card-header bg-secondary text-white h6">SEMAINE DE VENTE</div>

                <div class="card-body ">

                    <input type="hidden" name="idUser" id="idUser" value="<?php echo $user->data()->id; ?>" readonly>

                    <div class="input-group input-group-sm mb-3">

                        <div class="input-group-prepend"> <span class="input-group-text">Semaine du : </span> </div>

                        <input class="form-control form-control-sm" type="date" name="semaineVente" id="semaineVente" placeholder="Semaine de Vente" required>
                        <input type="hidden" id="dateFacturation" value="<?php echo $_GET['semaineVente']; ?>" readonly>

                    </div>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Lieu de vente</span> </div>
                        <select class="form-control form-control-sm" name="lieuVente" id="lieuVente" required>
                                <option value="Lomé_">Lomé </option>
                                <option value="Lomé_Kégué">Lomé - Kégué</option>
                                <option value="Lomé_Lomégan">Lomé - Lomégan</option>
                                <option value="Vogan_Ferme_CREATO">Vogan - Ferme CREATO</option>
                        </select>

                    </div>

                    <button type="button" class="form-control btn btn-primary" onclick="dateValid()"> Continuer  </button>

                </div>
        
            </div>    <!-- Card fin  --> 

    </div>

</div>

</div>


<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<?php
if (!isset($_GET['semaineVente']) || !isset($_GET['X_lieuVente'])) { exit;} 
else 
{ 
    $semaineVente = str_replace("-", "", $_GET['semaineVente']); 
    $semaineVenteOrig = $_GET['semaineVente'];
    $X_lieuVente = $_GET['X_lieuVente'];


function dateFormatToFR($data) 
{
	if (strlen($data) == 0 || $data == '0000-00-00' || is_null($data) )
	{
	$data = "N/A";
	}
	else 
	{
	$data = strtotime($data);
	//setlocale(LC_TIME, "fr_FR.utf8", "fra");
	setlocale(LC_TIME, 'fr_FR');
	$data = strftime("%d %B %Y",$data);
	}
	return utf8_encode($data);
}

function priceFormatToCFA($data) 
{
    echo number_format($data,0,"."," ");
}

$X_factExist = false;

function funcCreateFact($theFactNumber) {
    global $appDBconn, $X_factExist;
    $Xquery = "SELECT * FROM appFactVente WHERE numFact='".$theFactNumber."' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) < 1) { 
        $X_factExist = false;
        ?>
        <button type="button" class="btn btn-outline-success btn-sm" id="btnCreateFact-<?php echo $theFactNumber; ?>" 
            onclick="createFact('<?php echo $theFactNumber; ?>')">
            <i id="btnCreateFactLogo-<?php echo $theFactNumber; ?>" class="fa fa-plus-square" ></i>
        </button>
        <?php
    }
    else
    {
        $X_factExist = true;
        ?>
        <button type="button" class="btn btn-outline-primary btn-sm" id="btnPrintFact-<?php echo $theFactNumber; ?>" 
            data-toggle="modal" data-target="#fact-<?php echo $theFactNumber; ?>">
            <i class="fa fa-print"></i>
        </button>
        <?php
    }
}

// $mntTotalClt = $qteTotalClt = 0;
function funcQteTotalClt($theFactNmber) {
    global $appDBconn;
    $Xquery = "SELECT qte FROM appFactVenteDetail WHERE numFact='".$theFactNmber."' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 

        while($row = mysqli_fetch_assoc($Xresult)) 
        {    
          $qteTotalCltTmp = $row["qte"];
          $qteTotalClt += $qteTotalCltTmp;
        }

        echo $qteTotalClt; ?> <i class="fa fa-database text-success" ></i><?php

    } else { echo 0;}
}

function funcMntTotalClt($theFactNmber) {
    global $appDBconn;
    $Xquery = "SELECT qte, prixCatOeuf FROM appFactVenteDetail WHERE numFact='".$theFactNmber."' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 

      while($row = mysqli_fetch_assoc($Xresult)) 
      {    
        $mntTotalCltTmp = $row["qte"] * $row["prixCatOeuf"];
        $mntTotalClt += $mntTotalCltTmp;
      }

      echo priceFormatToCFA($mntTotalClt); ?> FCFA <i class="fa fa-database text-success" ></i><?php

    } else { echo 0;}
}

function funcMntGlobal($theDate) {
    global $appDBconn;
    $Xquery = "SELECT qte, prixCatOeuf FROM appFactVenteDetail WHERE date='".$theDate."' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 

      while($row = mysqli_fetch_assoc($Xresult)) 
      {    
        $mntGlobalTmp = $row["qte"] * $row["prixCatOeuf"];
        $mntGlobal += $mntGlobalTmp;
      }

    } else { echo 0;}

    echo priceFormatToCFA($mntGlobal); ?> FCFA <i class="fa fa-database text-success" ></i><?php
}

function funcMntGlobalLomegan($theDate) {
    global $appDBconn;
    $Xquery = "SELECT qte, prixCatOeuf FROM appFactVenteDetail WHERE date='".$theDate."' AND lieu='Lomé_Lomégan' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 

      while($row = mysqli_fetch_assoc($Xresult)) 
      {    
        $mntGlobalTmp = $row["qte"] * $row["prixCatOeuf"];
        $mntGlobal += $mntGlobalTmp;
      }

    } else { echo 0;}

    echo priceFormatToCFA($mntGlobal); ?> FCFA <i class="fa fa-database text-success" ></i><?php
}

function funcMntGlobalkegue($theDate) {
    global $appDBconn;
    $Xquery = "SELECT qte, prixCatOeuf FROM appFactVenteDetail WHERE date='".$theDate."' AND lieu='Lomé_Kégué' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 

      while($row = mysqli_fetch_assoc($Xresult)) 
      {    
        $mntGlobalTmp = $row["qte"] * $row["prixCatOeuf"];
        $mntGlobal += $mntGlobalTmp;
      }

    } else { echo 0;}

    echo priceFormatToCFA($mntGlobal); ?> FCFA <i class="fa fa-database text-success" ></i><?php
}

function funcMntGlobalVogan($theDate) {
    global $appDBconn;
    $Xquery = "SELECT qte, prixCatOeuf FROM appFactVenteDetail WHERE date='".$theDate."' AND lieu='Vogan_Ferme_CREATO' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 

      while($row = mysqli_fetch_assoc($Xresult)) 
      {    
        $mntGlobalTmp = $row["qte"] * $row["prixCatOeuf"];
        $mntGlobal += $mntGlobalTmp;
      }

    } else { echo 0;}

    echo priceFormatToCFA($mntGlobal); ?> FCFA <i class="fa fa-database text-success" ></i><?php
}

function funcLastCatQteClt($XidClt, $XCatName) {
    global $appDBconn;
    $Xquery = "SELECT qte FROM appFactVenteDetail 
                WHERE idClt='".$XidClt."' AND nomCatOeuf='".$XCatName."' ORDER BY id DESC LIMIT 1";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 
        $row = mysqli_fetch_assoc($Xresult);
        $qte = $row["qte"];
    }
    else
    { $qte = 0; }

    echo $qte;
}

$qteTotalReparti = 0;
function funcQteTotalReparti($XCatName, $XqteCharm) {
    global $appDBconn, $qteTotalReparti, $semaineVenteOrig;
    $Xquery = "SELECT SUM(qte) AS qteTotalReparti FROM appFactVenteDetail 
                WHERE date='".$semaineVenteOrig."' AND nomCatOeuf='".$XCatName."' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

	if (mysqli_num_rows($Xresult) > 0) { 
        $row = mysqli_fetch_assoc($Xresult);
        $qteTotalReparti = $row["qteTotalReparti"] ;
    }

    echo $XqteCharm - $qteTotalReparti;
}

$factMntTotalClt = 0;
function funcFactTableClt($theFactNmber) {
    global $appDBconn, $factMntTotalClt;
    $Xquery = "SELECT nomCatOeuf, qte, prixCatOeuf FROM appFactVenteDetail WHERE numFact='".$theFactNmber."' ";
    $Xresult = mysqli_query($appDBconn, $Xquery);
    $factMntTotalClt = 0;

	if (mysqli_num_rows($Xresult) > 0) { 

        ?>
              <table width="100%" border="1" class="factDetailTab">
                <tr class="factTabTitle">
                <td>CATÉGORIE</td>
                <td>QUANTITÉ</td>
                <td>PRIX UNITAIRE</td>
                <td>PRIX TOTAL</td>
                </tr>
        <?php

      while($row = mysqli_fetch_assoc($Xresult)) 
      {    
        $factMntTotalCltTmp = $row["qte"] * $row["prixCatOeuf"];
        $factMntTotalClt += $factMntTotalCltTmp;
      
      ?> 

                <tr>
                <td  width="30%"> <?php echo $row["nomCatOeuf"]; ?></td>
                <td width="20%"> <?php echo $row["qte"]; ?></td>
                <td width="20%"> <?php echo priceFormatToCFA($row["prixCatOeuf"]); ?> CFA</td>
                <td  width="30%"> <?php echo priceFormatToCFA($factMntTotalCltTmp); ?> CFA</td>
                </tr>
      <?php
      }

    ?>
    </table>
    <?php
    }
}

?>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<div class="bg-warning sticky-top text-center py-2 mb-3">

    <?php
    $query3 = $db->query("SELECT * FROM appCatOeuf WHERE qte > 0");
    $results3 = $query3->results(true);
    $xx_dateVente = $results3[0]["dateVente"];
    ?>
    <h4 class="text-primary">Chargement et état de repartition par catégorie [ Semaine du <?php echo dateFormatToFR($xx_dateVente); ?> ]</h4>
    <div class="mx-auto" style="width: 65%;">
        <ul class="list-group list-group-horizontal">
        <?php
        $X_catArray = array();

        foreach ($results3 as $record3)
        {
            array_push($X_catArray, $record3["nom"]);

            $dateChargm = $record3["dateVente"];
            $nomCatCharg = $record3["nom"];
            $qteCatCharg = $record3["qte"];
            // echo $nomCat. "<br>";
            ?>
                <li class="list-group-item px-2 py-2 mr-1">
                    <?php echo $nomCatCharg; ?>
                    <span class="badge badge-primary" id="<?php echo 'all_'.$nomCatCharg.'_sum_fixed'; ?>"><?php echo $qteCatCharg; ?></span>
                    <div class="text-muted" style="font-size: 9px;"><?php echo $dateChargm; ?></div>
                    <div class="bg-success">
                        <span class="badge badge-success" id="<?php echo 'all_'.$nomCatCharg.'_sum'; ?>"><?php funcQteTotalReparti($nomCatCharg, $qteCatCharg); ?></span>
                    </div>
                </li>
        <?php
        }
        ?>
                         
        </ul>
    </div>

</div>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<?php

    // $limitChangeDate = date('Y-m-d', strtotime("+2 day", strtotime($dateChargm)));

    // if ($limitChangeDate > $semaineVenteOrig) 
    // {
        ?>
        <!-- <div class="text-center py-5">
            <h3 class="text-danger">Impossible d'accéder aux données dont la date de vente dépasse 2 jours !</h3>
            <h5>Pour accéder aux données de vente en toute sécurité, veuillez cliquer sur le bouton ci-dessous.</h5>
            <a href="appFactVente.php" class="btn btn-success my-5"><h3>Registre des factures</h3></a>
        </div> -->
        <?php
        // exit;
    // }

?>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<div class="container-fluid">

<!-- ===================================================================== -->
<!-- ===================================================================== -->
<!-- ===================================================================== -->

<div class="row mb-3">

    <div class="col-md-12">
        <button class="btn btn-dark btn-sm text-white" onclick="simulateRepartition()">
            <i id="btnSimulateRepartitionLogo" class="fa fa-cogs"></i> Simuler la repartition avec les anciennes quantités
        </button>

        <button class="btn btn-success btn-sm" onclick="createAllFact()">
            <i id="btnCreateAllFactLogo" class="fa fa-plus-square"></i> Créer les factures automatiquement
        </button>   

        <a href="https://groupecreato.online/users/appAllFact.php?semaineVente=<?php echo $semaineVenteOrig; ?>" 
        target="_blanc" class="btn btn-primary btn-sm" onclick="showAllFact()">
            <i id="btnShowAllFactLogo" class="fa fa-print"></i> Afficher toutes les factures
        </a> 

        <button class="btn btn-warning btn-sm" onclick="clickBtnCreateAllFactDetails()">
            <i id="btnCreateAllFactDetailsLogo" class="fa fa-cart-plus"></i> Enregistrer automatiquement la repartition
        </button>  
    </div>

</div>

<div class="row mb-3">

    <div class="col-md-12">  
        <div class="alert alert-primary" id="resultAllFact-<?php echo $semaineVenteOrig; ?>" style="display:none;"></div>
        <div class="alert alert-primary" id="resultSimulation" style="display:none;"></div>
    </div>

</div>

<!-- ===================================================================== -->
<!-- ===================================================================== -->
<!-- ===================================================================== -->

<div class="row">

    <div class="col-md-12 mb-3">

        <div class="table-responsive">

                <table id="tab" class="table table-hover table-bordered" width="100%" cellspacing="0" style="font-size: 14px; color: black;">

                        <thead>

                            <tr>
                                <th width="12%">Client(e)</th>

                                <th width="12%">N° Facture</th>

                                <th>Catégorie/Prix/Quantité</th>

                                <th width="5%">Total<br>Plateaux</th>

                                <th width="10%">Montant<br>total<br>à payer</th>
                            </tr>

                        </thead>
                        <tbody>

<?php

if ($X_lieuVente == 'Lomé_')
{ $query = $db->query("SELECT id, nom, lieuAchatFav FROM appClt WHERE lieuAchatFav LIKE '".$X_lieuVente."%' ORDER BY nom ASC"); }
else 
{ $query = $db->query("SELECT id, nom, lieuAchatFav FROM appClt WHERE lieuAchatFav='".$X_lieuVente."' ORDER BY nom ASC"); }

$results = $query->results(true);

$X_factArray = array();

foreach ($results as $record)
{

$nomClt = $record["nom"];
$lieuAchatFav = $record["lieuAchatFav"];
$idClt = $record["id"];
$numFactClt = 'CREATO_'.$idClt.'_'.$semaineVente;

array_push($X_factArray, $numFactClt);
//echo $record["nom"]. "<br>";

?>
                        <tr>
                            <td>
                                <?php echo $nomClt; ?> <br>
                                <input type="hidden" value="<?php echo $nomClt; ?>" id="nomClt-<?php echo $numFactClt; ?>" readonly>
                                <input type="hidden" value="<?php echo $idClt; ?>" id="idClt-<?php echo $numFactClt; ?>" readonly>

                                <select id="lieuAchatFavClt-<?php echo $numFactClt; ?>">
                                    <option value="<?php echo $lieuAchatFav; ?>"><?php echo $lieuAchatFav; ?></option>
                                    <option value="Lomé_Lomégan">Lomé - Lomégan</option>
                                    <option value="Lomé_Kégué">Lomé - Kégué</option>
                                    <option value="Vogan_Ferme_CREATO">Vogan - Ferme CREATO</option>
                                </select>

                                <div class="alert alert-success text-center" id="resultSuccess-<?php echo $numFactClt; ?>" style="display:none;"></div>
                                <div class="alert alert-danger text-center" id="resultError-<?php echo $numFactClt; ?>" style="display:none;"></div>
                            </td>
                            <td>
                                <?php echo $numFactClt; ?> <br>
                                <?php funcCreateFact($numFactClt); ?>
                                
                            </td>
                            <td>
<?php
$query2 = $db->query("SELECT nom, prix FROM appCatOeuf WHERE qte > 0");
$results2 = $query2->results(true);
?>
<div class="d-flex flex-row">
<?php

foreach ($results2 as $record2)
{
    $nomCat = $record2["nom"];
    $qteCat = $record2["qte"];
    $prixCat = $record2["prix"];
    ?>

        <div class="mr-1 bg-light text-dark border text-center">
            <?php echo $nomCat; ?> <br>
            <input type='text' id="<?php echo 'prixClt-'.$nomCat.'-'.$numFactClt; ?>"  
            name="<?php echo 'prixClt-'.$nomCat.'-'.$numFactClt; ?>" 
            value='<?php echo $prixCat; ?>' size='6' \> <br>

            <input type="text" class="<?php echo $nomCat; ?>" id="<?php echo 'qteClt-'.$nomCat.'-'.$numFactClt; ?>" 
            onkeyup="allCatSum('<?php echo $nomCat; ?>', '<?php echo $numFactClt; ?>')" 
            name="<?php echo 'qteClt-'.$nomCat.'-'.$numFactClt; ?>" 
            value="<?php funcLastCatQteClt($idClt, $nomCat); ?>"  size="6" \> <br>

            <span class="glyphicon glyphicon-ok-sign text-success" id="resultSuccess-<?php echo $nomCat; ?>-<?php echo $numFactClt; ?>" style="display:none;"></span>
            <span class="glyphicon glyphicon-remove-sign text-danger" id="resultError-<?php echo $nomCat; ?>-<?php echo $numFactClt; ?>" style="display:none;"></span>
        </div>

        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@ Debut Modal @@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

        <div class="modal" id="fact-<?php echo $numFactClt; ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <!-- Modal body -->
            <div class="modal-body">
                <table id="tabFact-<?php echo $numFactClt; ?>">
                    <tr>
                        <td width="30%">
                            <img src="appImages/logo0.png" width="100%"><br>
                            Cel : (+228) 90 36 85 44 <br>
                            Cel : (+228) 97 41 45 73
                        </td>
                        <td colspan="2"  align="right" class="factDateZone">
                            <h6>Date : <?php echo dateFormatToFR($semaineVenteOrig); ?></h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center factRefLigne">
                            <hr>
                            <h4>FACTURE N° <?php echo $numFactClt; ?></h4>
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="py-1">
                            <p>CLIENT : <?php echo $nomClt; ?></p>
                            <p>LIEU DE VENTE : <?php echo $lieuAchatFav; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <?php funcFactTableClt($numFactClt); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" class="factTotal">
                            <h6>TOTAL À PAYER : <?php echo priceFormatToCFA($factMntTotalClt); ?> CFA </h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"  align="right" class="factSigZone">
                            <h6>LA DIRECTION</h6>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="printFact('<?php echo $numFactClt; ?>')"> <i class="fa fa-print"></i> Imprimer </button>
            </div>

            </div>
        </div>
        </div>

        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@ Fin Modal @@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

    <?php
}

?>

<div class="mr-1 text-white text-center my-auto <?php if(!$X_factExist) {echo "d-none";} ?>"  id="divCreateFactDetails-<?php echo $numFactClt; ?>">
    <button type="button" class="btn btn-warning btn-sm py-2" onclick="searchFactDetails('<?php echo $numFactClt; ?>')" id="btnCreateFactDetails-<?php echo $numFactClt; ?>">
        <i id="btnCreateFactDetailsLogo-<?php echo $numFactClt; ?>" class="fa fa-cart-plus fa-2x"></i> 
    </button>   
</div>

</div>

                            </td>
                            
                            <td>
                                <div id="<?php echo 'qteTotalClt-'.$numFactClt; ?>">
                                <?php funcQteTotalClt($numFactClt); ?>
                                </div>
                            </td>
                            <td>
                                <div id="<?php echo 'mntTotalClt-'.$numFactClt; ?>">
                                <?php funcMntTotalClt($numFactClt); ?>
                                </div>
                            </td>
                        </tr>
                        

<?php 
}
?>   
<tr>
    <td colspan="5" > <h6> MONTANT TOTAL KÉGUÉ : <?php echo funcMntGlobalkegue($semaineVenteOrig); ?></h6></td>
</tr>
<tr>
    <td colspan="5" > <h6> MONTANT TOTAL LOMÉGAN : <?php echo funcMntGlobalLomegan($semaineVenteOrig); ?></h6></td>
</tr>
<tr>
    <td colspan="5" > <h6> MONTANT TOTAL VOGAN : <?php echo funcMntGlobalVogan($semaineVenteOrig); ?></h6></td>
</tr>
<tr>
    <td colspan="5" > <h6> MONTANT GLOBAL : <?php echo funcMntGlobal($semaineVenteOrig); ?></h6></td>
</tr>
                        </tbody>

                    </table>

                </div>

        </div>    <!-- Card fin  -->

</div> 

<!-- ===================================================================== -->
<!-- ===================================================================== -->
<!-- ===================================================================== -->

</div>

<!-- ######################################################################################### -->

<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>-->

<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>



<script>

$(document).ready(function () {    

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@  

    console.log("Start...");

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@  

});

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function allCatSum(theCatName, theNumFactClt)
{
    var sumCat=0;
    var sumQteClt=0;
    var sumMntClt=0;

    $('.'+theCatName).each(function(){
        var item_val_all=parseFloat($(this).val());

        if(isNaN(item_val_all)){
            item_val_all=0;
        }
        
        sumCat+=item_val_all;
        var qteCatCharg = $('#all_'+theCatName+'_sum_fixed').html();
        var qteCatChargAvailable = qteCatCharg-sumCat;
        $('#all_'+theCatName+'_sum').html(qteCatChargAvailable);
    });

    $('input[id^="qteClt-"][id$="'+theNumFactClt+'"]').each(function(){
        var qte_val_clt=parseFloat($(this).val());
        var prix_val_clt=$('#prixClt-'+this.className+'-'+theNumFactClt).val();

        // console.log('prixClt-'+this.className+'-'+theNumFactClt);

        if(isNaN(qte_val_clt)){
            qte_val_clt=0;
        }

        if(isNaN(prix_val_clt)){
            prix_val_clt=0;
        }
        
        sumQteClt+=qte_val_clt;
        sumMntClt+=qte_val_clt*prix_val_clt;

        $('#qteTotalClt-'+theNumFactClt).html(sumQteClt);
        $('#mntTotalClt-'+theNumFactClt).html(sumMntClt);
    });
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function createFact(theNumFact) {

    $("#btnCreateFactLogo-"+theNumFact).removeClass("fa-plus-square").addClass("fa-spinner fa-spin");

    var dataToSend = new FormData();
    dataToSend.append('userLog', $("#idUser").val());
    dataToSend.append('date', $("#dateFacturation").val());
    dataToSend.append('nomClt', $("#nomClt-"+theNumFact).val());
    dataToSend.append('idClt', $("#idClt-"+theNumFact).val());
    dataToSend.append('numFact', theNumFact);
    dataToSend.append('lieu', $("#lieuAchatFavClt-"+theNumFact).val());
    dataToSend.append('opera', 'addFactData');

    // console.log(dataToSend);

    $.ajax({
    type: 'POST',
    url: "<?=$us_url_root?>users/api/facturationReq",
    data: dataToSend,
    //cache: false,
    datatype: 'json',
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    timeout: 10000,
        success: function (data) {
        // console.log(data.status);

            if (data.status == 1)
            {
                
                $("#resultSuccess-"+theNumFact).html(data.status_message);
                $("#resultSuccess-"+theNumFact).show();
                $("<button type=\"button\" class=\"btn btn-outline-primary btn-sm\" id=\"btnPrintFact-"+theNumFact+"\"  \
                        data-toggle=\"modal\" data-target=\"#fact-"+theNumFact+"\">  \
                        <i class=\"fa fa-print\"></i>   \
                    </button>").insertAfter("#btnCreateFact-"+theNumFact);
                $("#btnCreateFact-"+theNumFact).remove();
                $("#divCreateFactDetails-"+theNumFact).removeClass("d-none");
                setTimeout(function(){ $("#resultSuccess-"+theNumFact).hide(); }, 2000); 
            }
            else
            {
                $("#btnCreateFactLogo-"+theNumFact).removeClass("fa-spinner fa-spin").addClass("fa-plus-square");
                $("#resultError-"+theNumFact).html(data.status_message);
                $("#resultError-"+theNumFact).show();
                setTimeout(function(){ $("#resultError-"+theNumFact).hide(); }, 10000); 
            }
        },

        error: function (request,error) {
            // This callback function will trigger on unsuccessful action
            alert('Problème de connexion, veuillez ressayer!');
            //alert(error);
        }
    });
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function addFactDetails(theNumFact, XtheCatName, Xqte_val_clt, Xprix_val_clt) {

    $("#btnCreateFactDetailsLogo-"+theNumFact).removeClass("fa-cart-plus").addClass("fa-spinner fa-spin");

    var dataToSend = new FormData();
    dataToSend.append('userLog', $("#idUser").val());
    dataToSend.append('date', $("#dateFacturation").val());
    dataToSend.append('nomClt', $("#nomClt-"+theNumFact).val());
    dataToSend.append('idClt', $("#idClt-"+theNumFact).val());
    dataToSend.append('numFact', theNumFact);
    dataToSend.append('lieu', $("#lieuAchatFavClt-"+theNumFact).val());
    dataToSend.append('prixCatOeuf', Xprix_val_clt);
    dataToSend.append('nomCatOeuf', XtheCatName);
    dataToSend.append('qte', Xqte_val_clt);
    dataToSend.append('opera', 'addFactDetailsData');

    // console.log(data.status);
    // console.log(dataToSend);

    $.ajax({
    type: 'POST',
    url: "<?=$us_url_root?>users/api/facturationReq",
    data: dataToSend,
    //cache: false,
    datatype: 'json',
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    timeout: 10000,
        success: function (data) {
        //console.log(data.status); 

            if (data.status == 1)
            {
                $("#btnCreateFactDetailsLogo-"+theNumFact).removeClass("fa-spinner fa-spin").addClass("fa-cart-plus");
                $("#resultSuccess-"+theNumFact).html(data.status_message);
                $("#resultSuccess-"+XtheCatName+"-"+theNumFact).show();
                setTimeout(function(){ $("#resultSuccess-"+XtheCatName+"-"+theNumFact).hide(); }, 120000); 
            }
            else
            {
                $("#btnCreateFactDetailsLogo-"+theNumFact).removeClass("fa-spinner fa-spin").addClass("fa-cart-plus");
                $("#resultError-"+theNumFact).html(data.status_message);
                $("#resultError-"+XtheCatName+"-"+theNumFact).show();
                setTimeout(function(){ $("#resultError-"+XtheCatName+"-"+theNumFact).hide(); }, 300000); 
            }
        },

        error: function (request,error) {
            // This callback function will trigger on unsuccessful action
            alert('Problème de connexion, veuillez ressayer!');
            //alert(error);
        }

    });

}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function createAllFact() {

var theFactDate = $("#dateFacturation").val();
var dateNumFact = <?php echo $semaineVente; ?>;

$("#btnCreateAllFactLogo").removeClass("fa-plus-square").addClass("fa-spinner fa-spin");

var dataToSend = new FormData();
dataToSend.append('userLog', $("#idUser").val());
dataToSend.append('date', theFactDate);
dataToSend.append('dateNumFact', dateNumFact);
dataToSend.append('opera', 'addAllFactData');

// console.log(dataToSend, theFactDate);

    $.ajax({
    type: 'POST',
    url: "<?=$us_url_root?>users/api/facturationReq",
    data: dataToSend,
    //cache: false,
    datatype: 'json',
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    timeout: 10000,
        success: function (data) {
        // console.log(data.status);

            $("#resultAllFact-"+theFactDate).html(data);
            $("#resultAllFact-"+theFactDate).show();
            setTimeout(function(){ $("#resultAllFact-"+theFactDate).hide(); }, 10000); 
            $("#btnCreateAllFactLogo").removeClass("fa-spinner fa-spin").addClass("fa-plus-square");
        },

        error: function (request,error) {
            // This callback function will trigger on unsuccessful action
            alert('Problème de connexion, veuillez ressayer!');
            // alert(error);
        }
    });
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function searchFactDetails(theNumFactClt)
{

    $("#btnCreateFactDetailsLogo-"+theNumFactClt).removeClass("fa-cart-plus").addClass("fa-spinner fa-spin");

    $('input[id^="qteClt-"][id$="'+theNumFactClt+'"]').each(function(){
        var qte_val_clt=parseFloat($(this).val());
        var theCatName = this.className;
        var prix_val_clt=$('#prixClt-'+theCatName+'-'+theNumFactClt).val();

        // console.log('prixClt-'+this.className+'-'+theNumFactClt);

        if(isNaN(qte_val_clt)){
            qte_val_clt=0;
        }

        if(isNaN(prix_val_clt)){
            prix_val_clt=0;
        }

        if (qte_val_clt > 0 && prix_val_clt > 0) {
            addFactDetails(theNumFactClt, theCatName, qte_val_clt, prix_val_clt)
        }
    });
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function simulateRepartition() {

    $("#btnSimulateRepartitionLogo").removeClass("fa-cogs").addClass("fa-spinner fa-spin");
    $("#resultSimulation").show();
    $("#resultSimulation").html("...VEUILLEZ PATIENTER QUELQUES SECONDES &#128519;");
    alert('Début de la Simulation...Veuillez cliquer sur le bonton OK pour démarrer');

    var X_catArray = <?php echo json_encode($X_catArray); ?>;
    
    setTimeout(function(){ 

        $.each(X_catArray, function (index, value) {
        // console.log(value);
        // setTimeout(function(){ $("#resultSimulation").html("Simulation de la Categorie "+value); }, 1000);
        $('.'+value).focus().trigger('keyup');
        });

        alert('Simulation de la repartition terminée');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        $("#resultSimulation").hide();
        $("#btnSimulateRepartitionLogo").removeClass("fa-spinner fa-spin").addClass("fa-cogs");

    }, 5000);
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function printFact(theNumFactClt)
{
   var divToPrint=document.getElementById("tabFact-"+theNumFactClt);
   var htmlToPrint = '' +
        '<style type="text/css">' +
        '.factDetailTab {' +
        'border:1px solid #000;' +
        'margin-top:10px;' +
        'margin-bottom:10px;' +
        'text-align:center;' +
        '}' +
        '.factTabTitle {' +
        'font-weight:bold;' +
        '}' +
        '.factRefLigne {' +
        'font-size:26px;' +
        'text-align:center;' +
        '}' +
        '.factTotal {' +
        'font-size:26px;' +
        'text-align:right;' +
        '}' +
        '.factSigZone {' +
        'font-size:22px;' +
        'text-align:right;' +
        'padding-top:10px;' +
        '}' +
        '.factDateZone {' +
        'font-size:22px;' +
        'text-align:right;' +
        'vertical-align:bottom;' +
        '}' +
        '</style>';
   htmlToPrint += divToPrint.outerHTML;
   newWin= window.open("");
   newWin.document.write(htmlToPrint);
   newWin.print();
   newWin.close();
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function clickBtnCreateAllFactDetails()
{

    var X_factArray = <?php echo json_encode($X_factArray); ?>;
    var X_factArrayLength = X_factArray.length - 1;
    // console.log(X_factArrayLength);

    $("#btnCreateAllFactDetailsLogo").removeClass("fa-cart-plus").addClass("fa-spinner fa-spin");
    $("#resultSimulation").show();
    $("#resultSimulation").html("...VEUILLEZ PATIENTER QUELQUES SECONDES &#128519;");
    alert('Début des enregistrements...Veuillez cliquer sur le bonton OK pour démarrer');
    
    setTimeout(function(){ 

        $.each(X_factArray, function (index, value) {
        
        // $('#btnCreateFactDetails-'+value).trigger('click');
        // setTimeout(function(){ $("#resultSimulation").html("Simulation de la Categorie "+value); }, 3000);

            setTimeout(function(){
                $('#btnCreateFactDetails-'+value).trigger('click');
                // console.log(index+'--------'+value);

                if (index == X_factArrayLength)
                {
                    alert('Les enregistrements sont terminés');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    $("#resultSimulation").hide();
                    $("#btnCreateAllFactDetailsLogo").removeClass("fa-spinner fa-spin").addClass("fa-cart-plus");
                }
            }, 3000);

        });

    }, 3000);
}

//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

</script>

<?php
}
?>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>