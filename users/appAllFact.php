<?php

require_once 'init.php';

// require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

if (!securePage($_SERVER['PHP_SELF'])) {

    die();

}

?>

<style>
body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "Tahoma";
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 10mm;
        margin: 5mm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        
    }
    .subpage {
        padding: 0.5cm;
        border: 1px gray solid;
        height: 277mm;  
        margin-bottom: 1cm;      
    }
    .factDetailTab {
        margin-top:20px;
        margin-bottom:20px;
        text-align:center;
        }
    .factTabTitle {
        font-weight:bold;
        }
    .factRefLigne {
        font-size:26px;
        text-align:center;
        }
    .factTotal {
        font-size:26px;
        text-align:right;
        }
    .factSigZone {
        font-size:26px;
        text-align:right;
        padding-top:50px;
        text-decoration: underline;
        }
    .factDateZone {
        font-size:24px;
        text-align:right;
        vertical-align:bottom;
        }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
        html, body {
            width: 210mm;
            height: 297mm;        
        }
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
        .subpage {
        margin-bottom: initial;      
        }
    }
</style>

<!-- ##################################################################### -->
<!-- ##################################################################### -->
<!-- ##################################################################### -->

<?php

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

<div class="page">

<?php

$query = "SELECT id, nom, lieuAchatFav FROM appClt ORDER BY nom ASC";

$Xresult = mysqli_query($appDBconn, $query);

while($row = mysqli_fetch_assoc($Xresult))
{
$nomClt = $row["nom"];
$lieuAchatFav = $row["lieuAchatFav"];
$idClt = $row["id"];
$numFactClt = 'CREATO_'.$idClt.'_'.$semaineVente;
?>

        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@ TABLE @@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
<div class="subpage">
                <table >
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
                        <td colspan="2" class="factRefLigne">
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
                        <td colspan="2" >
                        <?php funcFactTableClt($numFactClt); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" class="factTotal">
                            <h6>TOTAL À PAYER : <?php echo priceFormatToCFA($factMntTotalClt); ?> CFA </h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"  align="right" class="factSigZone" >
                            <h6>LA DIRECTION</h6>
                        </td>
                    </tr>
                </table>
</div>


<?php 
}
?>
        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@ TABLE @@@@@@@@@@@@@@@@@@ -->
        <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->

</div>

<!-- ===================================================================== -->
<!-- ===================================================================== -->
<!-- ===================================================================== -->

