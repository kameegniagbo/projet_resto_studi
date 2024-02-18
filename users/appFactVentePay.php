<?php

require_once 'init.php';

require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {

    die();

}

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
    function dateValid() {
        var semaineVente=document.getElementById('semaineVente').value;

        if (semaineVente) { window.location.href = "https://groupecreato.online/users/appFactVentePay.php?semaineVente="+semaineVente; }
        else 
        { alert('Veuillez selectionner une date'); }
    }

    function noDateValid() { window.location.href = "https://groupecreato.online/users/appFactVentePay.php"; }

</script>

<style>
.bg-light {
    background-color: #dadada!important;
}

.list-group-item {
   
    padding: 1px 5px 1px 5px;
    vertical-align: middle;
    text-align: center;
}

.list-group-item>.btn-sm {
    padding: 0px 5px 0px 5px;
}

.liWidth {
    width: 120px;
}

</style>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<div class="row">

	<div class="col-md-12">

	    <br>

	    <h3 >GESTION DES PAIEMENTS</h3>

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

                    <button type="button" class="btn btn-primary" onclick="dateValid()"> Fiche de solde - semaine selectionnée  </button>
                    <button type="button" class="btn btn-outline-primary" onclick="noDateValid()"> Fiche de solde global  </button>

                </div>
        
            </div>    <!-- Card fin  --> 

    </div>

</div>

</div>


<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<?php

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

function funcPrintFact($theFactNumber) {

        ?>
            <i class="fa fa-print text-primary mx-1" 
                id="btnPrintFact-<?php echo $theFactNumber; ?>" 
                data-toggle="modal" 
                data-target="#fact-<?php echo $theFactNumber; ?>" > 
            </i>
        <?php
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

function showNotSolvedFact($theNumFact, $theSumMntCatOeuf, $theDate, $theCltName, $theLieu, $theSumMntPay, $theRestFactToPay, $theIdClt, $theNomClt) {

?>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
<!-- @@@@@@@@@@@@@@@@@@ Debut Modal @@@@@@@@@@@@@@@@@@ -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<div class="modal" id="fact-<?php echo $theNumFact; ?>">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<!-- Modal body -->
<div class="modal-body">
<table id="tabFact-<?php echo $theNumFact; ?>">
    <tr>
        <td width="30%">
            <img src="appImages/logo0.png" width="100%"><br>
            Cel : (+228) 90 36 85 44 <br>
            Cel : (+228) 97 41 45 73
        </td>
        <td colspan="2"  align="right" class="factDateZone">
            <h6>Date : <?php echo dateFormatToFR($theDate); ?></h6>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-center factRefLigne">
            <hr>
            <h4>FACTURE N° <?php echo $theNumFact; ?></h4>
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="py-1">
            <p>CLIENT : <?php echo $theCltName; ?></p>
            <p>LIEU DE VENTE : <?php echo $theLieu; ?></p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <?php funcFactTableClt($theNumFact); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="right" class="factTotal">
            <h6>TOTAL À PAYER : <?php echo priceFormatToCFA($theSumMntCatOeuf); ?> CFA </h6>
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
<button type="button" class="btn btn-secondary" onclick="printFact('<?php echo $theNumFact; ?>')"> <i class="fa fa-print"></i> Imprimer </button>
</div>

</div>
</div>
</div>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
<!-- @@@@@@@@@@@@@@@@@@ Fin Modal @@@@@@@@@@@@@@@@@@ -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

<ul class="list-group list-group-horizontal" style="font-size: 16px;" >
    <li class="list-group-item" style="width:30px;">
        <span class="glyphicon glyphicon-ok-sign text-success" id="resultSuccess-<?php echo $theNumFact; ?>" style="display:none;"></span>
        <span class="glyphicon glyphicon-remove-sign text-danger" id="resultError-<?php echo $theNumFact; ?>" style="display:none;"></span>
    </li>
    <li class="list-group-item" style="width:270px;"><?php echo $theNumFact; funcPrintFact($theNumFact); ?> </li>
    <li class="list-group-item liWidth"><?php echo priceFormatToCFA($theSumMntCatOeuf); ?> </li>
    <li class="list-group-item liWidth"><?php echo priceFormatToCFA($theSumMntPay); ?> </li>
    <li class="list-group-item liWidth bg-warning"><?php echo priceFormatToCFA($theRestFactToPay); ?> </li>
    <li class="list-group-item">
    <input type="hidden" id="<?php echo 'nomClt-'.$theNumFact; ?>" value="<?php echo $theNomClt; ?>" \>
    <input type="hidden" id="<?php echo 'idClt-'.$theNumFact; ?>" value="<?php echo $theIdClt; ?>" \>
    <input type="hidden" id="<?php echo 'numFact-'.$theNumFact; ?>" value="<?php echo $theNumFact; ?>" \>
    <input type="text" id="<?php echo 'mntPay-'.$theNumFact; ?>" value=""  size="8" \>
    </li>
    <li class="list-group-item">
    <input type="date" id="<?php echo 'datePay-'.$theNumFact; ?>" value="" \>
    </li>
    <li class="list-group-item">
    <button type="button" class="btn btn-danger btn-sm" onclick="makePay('<?php echo $theNumFact; ?>')">
    <i id="btnMakePayLogo-<?php echo $theNumFact; ?>" class="fa fa-usd"></i> 
    </button>
    </li>
</ul>
   

<?php 

}

function funcCheckSold($theNumFact) {
    global $appDBconn;
    $Xquery = "SELECT SUM(mntPay) AS sumMntPay FROM appFactVentePay 
                WHERE numFact='".$theNumFact."'  ";
    $Xresult = mysqli_query($appDBconn, $Xquery);

    $row = mysqli_fetch_assoc($Xresult);
    $sumMntPay = $row["sumMntPay"];

    // if ($sumMntPay == 0) { $

    return $sumMntPay;
}

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
    </div>

</div>

<div class="row mb-3">

    <div class="col-md-12">  
    </div>

</div>

<!-- ===================================================================== -->
<!-- ===================================================================== -->
<!-- ===================================================================== -->

<div class="row">

    <div class="col-md-12 mb-3">

        <div class="table-responsive">

                <table id="tab" class="table table-hover table-bordered" width="100%" cellspacing="0" style="font-size: 11px; color: black;">

                        <thead>

                            <tr>
                                <th width="15%">Client(e)</th>

                                <th>N° Facture/Mnt.Total/Mnt.Payé</th>
                            </tr>

                        </thead>
                        <tbody>

<?php

$queryClt = $db->query("SELECT id, nom FROM appClt ORDER BY nom ASC");
$resultsClt = $queryClt->results(true);

foreach ($resultsClt as $recordClt)
{
$nomClt = $recordClt["nom"];
$idClt = $recordClt["id"];


?>
    <tr>
        <td style="vertical-align: middle;">
            <h6><?php echo $nomClt; ?></h6>
        </td>
        <td>
<?php

if (isset($_GET['semaineVente']) ) 
{
    $semaineVenteOrig = $_GET['semaineVente'];
    $queryVente = $db->query("SELECT SUM(mntCatOeuf) AS sumMntCatOeuf, date, nomClt, numFact, lieu 
                    FROM appFactVenteDetail 
                    WHERE nomClt='".$nomClt."' AND date='".$semaineVenteOrig."' 
                    GROUP BY numFact");
}
else 
{ 
    $queryVente = $db->query("SELECT SUM(mntCatOeuf) AS sumMntCatOeuf, date, nomClt, numFact, lieu 
                    FROM appFactVenteDetail 
                    WHERE nomClt='".$nomClt."' 
                    GROUP BY numFact");

}

$resultsVente = $queryVente->results(true);

foreach ($resultsVente as $recordVente)
{
$date = $recordVente["date"];
$lieu = $recordVente["lieu"];
$numFact = $recordVente["numFact"];
$sumMntCatOeuf = $recordVente["sumMntCatOeuf"];

$x_sumMntPay = funcCheckSold($numFact);

if ($sumMntCatOeuf > $x_sumMntPay)
{
    $x_restFactToPay = $sumMntCatOeuf - $x_sumMntPay;

    showNotSolvedFact($numFact, $sumMntCatOeuf, $date, $nomClt, $lieu, $x_sumMntPay, $x_restFactToPay, $idClt, $nomClt); 
}

}
}
?>  
        </td>
        
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

function makePay(theNumFact) {

    $("#btnMakePayLogo-"+theNumFact).removeClass("fa-money").addClass("fa-spinner fa-spin");

    var dataToSend = new FormData();
    dataToSend.append('userLog', $("#idUser").val());
    dataToSend.append('date', $("#datePay-"+theNumFact).val());
    dataToSend.append('nomClt', $("#nomClt-"+theNumFact).val());
    dataToSend.append('idClt', $("#idClt-"+theNumFact).val());
    dataToSend.append('numFact', $("#numFact-"+theNumFact).val());
    dataToSend.append('mntPay', $("#mntPay-"+theNumFact).val());
    dataToSend.append('opera', 'addPayData');

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
                $("#btnMakePayLogo-"+theNumFact).removeClass("fa-spinner fa-spin").addClass("fa-money");
                // $("#resultSuccess-"+theNumFact).html(data.status_message);
                $("#resultSuccess-"+theNumFact).show();
                setTimeout(function(){ $("#resultSuccess-"+theNumFact).hide(); }, 1200000); 
            }
            else
            {
                $("#btnMakePayLogo-"+theNumFact).removeClass("fa-spinner fa-spin").addClass("fa-money");
                // $("#resultError-"+theNumFact).html(data.status_message);
                $("#resultError-"+theNumFact).show();
                setTimeout(function(){ $("#resultError-"+theNumFact).hide(); }, 120000); 
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

</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>