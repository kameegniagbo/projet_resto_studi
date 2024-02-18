<?php
require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

////////////////////////// config default show
$idUser = $user->data()->id;
$data = fetchUserDetails(null,null,$idUser);
$anneeStat = $data->anneeStat;
$anneeStatPreviz = $anneeStat + 1;

///////////////////////// config year's data to show
if (isset($_GET['xAnneeStat']) && !isset($_GET['xIdUser'])) 
{
    $anneeStat = $_GET['xAnneeStat'];
    $anneeStatPreviz = $anneeStat + 1;
}


////////////////////////// config xuser's data to show
if (isset($_GET['xIdUser']) && !isset($_GET['xAnneeStat'])) 
{
    $idUser = $_GET['xIdUser'];
    $data = fetchUserDetails(null,null,$idUser);
    $anneeStat = $data->anneeStat;
    $anneeStatPreviz = $anneeStat + 1;
}


////////////////////////// config year's and xuser's data to show
if (isset($_GET['xIdUser']) && isset($_GET['xAnneeStat'])) 
{
    $idUser = $_GET['xIdUser'];
    $data = fetchUserDetails(null,null,$idUser);
    $anneeStat = $_GET['xAnneeStat'];
    $anneeStatPreviz = $anneeStat + 1;
}

// echo $anneeStat;
// echo ' | ';
// echo $idUser;

//########################################################################################

require_once "api/fieldsCheckingFunctions.php";

$totalRecette = $totalDepense = $totalReference = 0;

function sumRecette()
{
	global $appDBconn, $idUser, $totalRecette, $anneeStatPreviz;
	$query = "SELECT * FROM appBudgRecette WHERE idUser='".$idUser."' AND annee='".$anneeStatPreviz."' ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
    // output data of each row
      while($row = mysqli_fetch_assoc($result)) 
      {
        $totalTmp = $row["qte"] * $row["prixU"];
        $totalRecette += $totalTmp;
      }
    } 
    else 
    {
      echo "0x";
    }
}

function sumDepense()
{
	global $appDBconn, $idUser, $totalDepense, $anneeStatPreviz;
	$query = "SELECT * FROM appBudgDepense WHERE idUser='".$idUser."' AND annee='".$anneeStatPreviz."'  ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
    // output data of each row
      while($row = mysqli_fetch_assoc($result)) 
      {
        $totalTmp = $row["qte"] * $row["prixU"];
        $totalDepense += $totalTmp;
      }
    } 
    else 
    {
      echo "0";
    }
}

function sumReference()
{
	global $appDBconn, $totalReference, $idUser, $anneeStatPreviz;
	$query = "SELECT SUM(qte * ctUnit) AS sumX FROM appRefBudget WHERE idUser='".$idUser."' AND annee='".$anneeStatPreviz."'  ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
        $row = mysqli_fetch_assoc($result);
        $totalReference = $row["sumX"];
    } 
    else 
    {
      echo "0";
    }
}


sumRecette();

sumDepense();

sumReference();

$totalSold = $totalRecette - $totalDepense;

//########################################################################################

//include("chartjs/dashboard.php");

?>

<div class="row pt-3">
    <div class="col-12 col-md-12">

	    <div class="clearfix">
          <span class="float-left">
              <h3>TABLEAU DE BORD </h3> 
                <select class="form-control bg-primary text-white" style="width : auto;" id="anneeToShow" onchange="showAnneeSelected();">
                    <option>Choisir l'année à afficher</option>
                    <option>2021</option>
                    <option>2022</option>
                    <option>2023</option>
                    <option>2024</option>
                    <option>2025</option>
                    <option>2026</option>
                    <option>2027</option>
                    <option>2028</option>
                    <option>2029</option>
                    <option>2030</option>
                  </select>
          </span>
        </div>
	    
        <hr>
    </div> 
</div>

<!-- ######################################################################################## -->


<div class="row">

	<div class="col-12 col-md-12">

<!--========================================================================-->	    
	     <div class="row">
              
              <div class="input-group mb-3 input-group-sm col-md-2">
                 <div class="input-group-prepend">
                   <span class="input-group-text bg-primary text-white">ANNÉE</span>
                </div>
                <input class="form-control form-control-sm border border-primary" type="text" name="annee" id="annee" value="<?php echo $anneeStat; ?>" readonly>
              </div>
              
              <div class="input-group mb-3 input-group-sm col-md-2">
                 <div class="input-group-prepend">
                   <span class="input-group-text">RS</span>
                </div>
                <input class="form-control form-control-sm" type="text" name="nomRS" id="nomRS" value="<?php echo $user->data()->nomRS; ?>" readonly>
              </div>
              
              <div class="input-group mb-3 input-group-sm col-md-3">
                 <div class="input-group-prepend">
                   <span class="input-group-text">DS</span>
                </div>
                <input class="form-control form-control-sm" type="text" name="nomDS" id="nomDS" value="<?php echo $user->data()->nomDS; ?>" readonly>
              </div>
              
              <div class="input-group mb-3 input-group-sm col-md-2">
                 <div class="input-group-prepend">
                   <span class="input-group-text">CS</span>
                </div>
                <input class="form-control form-control-sm" type="text" name="nomCS" id="nomCS" value="<?php echo $user->data()->nomCS; ?>" readonly>
              </div>
              
              <div class="input-group mb-3 input-group-sm col-md-3">
                 <div class="input-group-prepend">
                   <span class="input-group-text">FS</span>
                </div>
                <input class="form-control form-control-sm" type="text" name="nomFS" id="nomFS" value="<?php echo $user->data()->nomFS; ?>" readonly>
              </div>
          </div>
<!--========================================================================-->

          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-3 col-md-3 mb-12 my-2">
              <div class="card border-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">PRÉVISION RECETTES <?php echo $anneeStatPreviz; ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo priceFormatToCFA($totalRecette);  ?></div>
                      <a href="appRecetteList.php">Détails</a>
                    </div>
                    <div class="col-auto">
                      <!--<i class="fa fa-dollar fa-2x text-gray-300"></i>-->
                      <img src="images/cfa.png" width="65px" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-3 mb-12 my-2">
              <div class="card border-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">PRÉVISION DÉPENSES <?php echo $anneeStatPreviz; ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo priceFormatToCFA($totalDepense);  ?></div>
                      <a href="appDepenseList.php">Détails</a>
                    </div>
                    <div class="col-auto">
                      <!--<i class="fa fa-dollar fa-2x text-gray-300"></i>-->
                      <img src="images/cfa.png" width="65px" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-md-3 mb-12 my-2">
              <div class="card border-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">PRÉVISION SOLDE <?php echo $anneeStatPreviz; ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo priceFormatToCFA($totalSold);  ?></div>
                      <a href="appSoldList.php">Détails</a>
                    </div>
                    <div class="col-auto">
                      <!--<i class="fa fa-dollar fa-2x text-gray-300"></i>-->
                      <img src="images/cfa.png" width="65px" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-3 mb-12 my-2">
              <div class="card bg-danger border-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">PRÉVISION RÉFÉRENCES <?php echo $anneeStatPreviz; ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo priceFormatToCFA($totalReference); ?></div>
                      <a href="appRefBudgetList.php">Détails</a>
                    </div>
                    <div class="col-auto">
                      <!--<i class="fa fa-dollar fa-2x text-gray-300"></i>-->
                      <img src="images/cfa.png" width="65px" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

        </div>
        <br>

          <!-- Content Row -->

<!-- ================================================================ -->


<!-- Content Row -->
<div class="row">

<div class="col-md-12 my-2">
  <div class="card border-primary shadow h-100 py-2">
    <div class="card-body">
        <div class="table-responsive">
<?php

$countDataRecette = $sumDataRecette = 0;
$countDataDepense = $sumDataDepense = 0;
$countDataRef = $sumDataRef = 0;
$countDep = 0;

function getDataRecette($idUser)
{
	global $countDataRecette, $sumDataRecette, $appDBconn, $anneeStatPreviz;
	$query = "SELECT nomFS, SUM(qte * prixU) AS sumX, COUNT(*) AS countX FROM appBudgRecette WHERE idUser='".$idUser."' AND annee='".$anneeStatPreviz."'  ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
	    $row = mysqli_fetch_assoc($result);
        $countDataRecette = $row["countX"];
        $sumDataRecette = $row["sumX"];
        //echo $sumDataRecette;
    } 
    else 
    {
        $countDataRecette = 0;
        $sumDataRecette = 0;
    }
}

function getDataDepense($idUser)
{
	global $countDataDepense, $sumDataDepense, $appDBconn, $anneeStatPreviz;
	$query = "SELECT nomFS, SUM(qte * prixU) AS sumX, COUNT(*) AS countX FROM appBudgDepense WHERE idUser='".$idUser."' AND annee='".$anneeStatPreviz."'  ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
	    $row = mysqli_fetch_assoc($result);
        $countDataDepense = $row["countX"];
        $sumDataDepense = $row["sumX"];
        
    } 
    else 
    {
        $countDataDepense = 0;
        $sumDataDepense = 0;
    }
}

function getDataRef($idUser)
{
	global $countDataRef, $sumDataRef, $appDBconn, $anneeStatPreviz;
	$query = "SELECT nomFS, 
	                SUM(qte * ctUnit) AS sumX, 
	                COUNT(*) AS countX FROM appRefBudget WHERE idUser='".$idUser."' AND annee='".$anneeStatPreviz."'  ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
	    $row = mysqli_fetch_assoc($result);
        $countDataRef = $row["countX"];
        $sumDataRef = $row["sumX"];
    } 
    else 
    {
        $countDataRef = 0;
        $sumDataRef = 0;
    }
}

function getDataDep($idUser)
{
	global $countDep, $appDBconn, $anneeStat;
	$query = "SELECT nomFS, COUNT(*) AS countX FROM appDepouill WHERE idUser='".$idUser."' AND annee='".$anneeStat."'  ";
	$result = mysqli_query($appDBconn, $query);

	if (mysqli_num_rows($result) > 0) 
	{
	    $row = mysqli_fetch_assoc($result);
        $countDep = $row["countX"];
    } 
    else 
    {
        $countDep = 0;
    }
}

?>                  
<table class="table table-bordered table-sm" style="font-size: 12px;">
    <thead>
      <tr >
        <th colspan="4" class="h4 bg-secondary text-center text-white">PROGRESSION DE LA COLLECTE DES DONNÉES</th>
      </tr>
      <tr>
        <th style="text-align:right;" width="15%">DONNÉES RECETTES <?php echo $anneeStatPreviz; ?></th>
        <th style="text-align:right;" width="15%">DONNÉES DÉPENSES <?php echo $anneeStatPreviz; ?></th>
        <th style="text-align:right;" width="15%">DONNÉESRÉFÉRENCES <?php echo $anneeStatPreviz; ?></th>
        <th style="text-align:right;" width="15%">DONNÉES DÉPOUILLEMENTS <?php echo $anneeStat; ?></th>
      </tr>
    </thead>
    <tbody>
<?php
   getDataRecette($idUser);
   getDataDepense($idUser);
   getDataRef($idUser);
   getDataDep($idUser);
?>
    <tr>

        <td style="text-align:right;">
            <a href="appRecetteList.php">  <?php  echo priceFormatToCFA($sumDataRecette); ?></a><br>

            <div class="clearfix">
                <span class="float-left pl-4" style="font-size: 9px;"><?php  echo number_format((($countDataRecette * 100) / 38), 2); ?>%</span>
                <div class="progress float-right" style="width: 60%;">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                        style="text-align: center; color: black; width: <?php  echo ($countDataRecette * 100) / 38; ?>%;" aria-valuenow="<?php  echo $countDataRecette; ?>">
                    </div>
                </div>
            </div>
        </td>
        <td style="text-align:right;">
            <a href="appDepenseList.php"> <?php  echo priceFormatToCFA($sumDataDepense); ?></a><br>

            <div class="clearfix">
                <span class="float-left pl-4" style="font-size: 9px;"><?php  echo number_format((($countDataDepense * 100) / 40), 2); ?>%</span>
                <div class="progress float-right" style="width: 60%;">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                        style="text-align: center; color: black; width: <?php  echo ($countDataDepense * 100) / 40; ?>%;" aria-valuenow="<?php  echo $countDataDepense; ?>">
                    </div>
                </div>            
            </div>
        </td>
        <td style="text-align:right;">
            <a href="appRefBudgetList.php"> <?php  echo priceFormatToCFA($sumDataRef); ?></a><br>
            
            <div class="clearfix">
                <span class="float-left pl-4" style="font-size: 9px;"><?php echo number_format((($countDataRef * 100) / 393), 2); ?>%</span>
                <div class="progress float-right" style="width: 60%;">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                        style="text-align: center; color: black; width: <?php  echo ($countDataRef * 100) / 393; ?>%;" aria-valuenow="<?php  echo $countDataRef; ?>">
                    </div>
                </div>            
            </div>
        </td>
        <td style="text-align:right;">
            <a href="appFichSynthList.php"> Afficher</a><br>

            <div class="clearfix ">
                <span class="float-left pl-4" style="font-size: 9px;"><?php echo number_format((($countDep * 100) / 16), 2); ?>%</span>
                <div class="progress float-right" style="width: 60%;">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                        style="text-align: center; color: black; width: <?php  echo ($countDep * 100) / 16; ?>%;" aria-valuenow="<?php  echo $countDep; ?>" >
                    </div>
                </div>            
            </div>
        </td>
    </tr>
                       
    </tbody>
</table>
                   
             </div> 
            </div>
          </div>
        </div>

    </div>
<br>

          <!-- Content Row -->

<!--========================================================================-->

          <!-- Content Row -->
        <!--  <div class="row">-->

        <!--    <div class="col-xl-3 col-md-3 mb-12">-->
        <!--      <div class="card border-primary shadow h-100 py-2">-->
        <!--        <div class="card-body">-->
        <!--          <div class="row no-gutters align-items-center">-->
        <!--            <div class="col mr-2">-->
        <!--              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">ABONNÉS EXPIRANTS</div>-->
        <!--              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php //echo "80000";  ?></div>-->
        <!--            </div>-->
        <!--            <div class="col-auto">-->
        <!--              <i class="fa fa-user fa-2x text-gray-300"></i>-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    </div>-->

        <!--    <div class="col-xl-3 col-md-3 mb-12">-->
        <!--      <div class="card border-success shadow h-100 py-2">-->
        <!--        <div class="card-body">-->
        <!--          <div class="row no-gutters align-items-center">-->
        <!--            <div class="col mr-2">-->
        <!--              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">XXX</div>-->
        <!--              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php //echo "75000";  ?></div>-->
        <!--            </div>-->
        <!--            <div class="col-auto">-->
        <!--              <i class="fa fa-calendar fa-2x text-gray-300"></i>-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    </div>-->

        <!--    <div class="col-xl-3 col-md-3 mb-12">-->
        <!--      <div class="card bg-danger border-danger shadow h-100 py-2">-->
        <!--        <div class="card-body">-->
        <!--          <div class="row no-gutters align-items-center">-->
        <!--            <div class="col mr-2">-->
        <!--              <div class="text-xs font-weight-bold text-uppercase mb-1">XXX</div>-->
        <!--              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php //echo "100000";  ?></div>-->
        <!--            </div>-->
        <!--            <div class="col-auto">-->
        <!--              <i class="fa fa-book fa-2x text-gray-300"></i>-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    </div>-->

        <!--    <div class="col-xl-3 col-md-3 mb-12">-->
        <!--      <div class="card border-danger shadow h-100 py-2">-->
        <!--        <div class="card-body">-->
        <!--          <div class="row no-gutters align-items-center">-->
        <!--            <div class="col mr-2">-->
        <!--              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">XXX</div>-->
        <!--              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php //echo "90000";  ?></div>-->
        <!--            </div>-->
        <!--            <div class="col-auto">-->
        <!--              <i class="fa fa-dollar fa-2x text-gray-300"></i>-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--      </div>-->
        <!--    </div>-->
            
        <!--</div>-->
        <!--<br>-->

          <!-- Content Row -->

<!-- ================================================================ -->
        
        <!-- Graphes -->
        
        <!--<div class="row">-->
        <!--   <div class="col-md-7">-->
        <!--     <div class="card card-default shadow" style="text-align: -webkit-center;"> <?php //echo $Bar;  ?> </div>-->
        <!--    </div>-->
        <!--    <div class="col-md-5">-->
        <!--     <div class="card card-default shadow" style="text-align: -webkit-center;"> <?php //echo $Pie; ?> </div>-->
        <!--    </div>-->
        <!--</div>-->

<!-- ================================================================ -->

	
</div>
<script src="chartjs/js/Chart.min.js"></script>
<script src="chartjs/js/driver.js"></script>
<script>
    (function () {
        loadChartJsPhp();
    })();
    
function showAnneeSelected()
{
    var anneeSelected = $("#anneeToShow option:selected").text();
    // console.log(anneeSelected);
    // console.log(searchParams.get('xIdUser'));
    
    // console.log(window.location.href);
    // console.log(window.location.search);
    // console.log(window.location.hostname);
    
    var searchParams = new URLSearchParams(window.location.search);
    
    if (searchParams.has('xAnneeStat') == false && searchParams.has('xIdUser') == false)
    {
        var goToUrl = "https://" + window.location.hostname + "/users/appDashboard.php?xAnneeStat=" + anneeSelected;
        // console.log(goToUrl);
        window.location.href = goToUrl;
    }
    
    if (searchParams.has('xAnneeStat') == true && searchParams.has('xIdUser') == false)
    {
        var goToUrl = "https://" + window.location.hostname + "/users/appDashboard.php?xAnneeStat=" + anneeSelected;
        // console.log(goToUrl);
        window.location.href = goToUrl;
    }
    
    if (searchParams.has('xAnneeStat') == false && searchParams.has('xIdUser') == true)
    {
        var xIdUser = searchParams.get('xIdUser');
        var goToUrl = "https://" + window.location.hostname + "/users/appDashboard.php?xIdUser=" + xIdUser + "&xAnneeStat=" + anneeSelected;
        // console.log(goToUrl);
        window.location.href = goToUrl;
    }
    
    if (searchParams.has('xAnneeStat') == true && searchParams.has('xIdUser') == true)
    {
        var xIdUser = searchParams.get('xIdUser');
        var goToUrl = "https://" + window.location.hostname + "/users/appDashboard.php?xIdUser=" + xIdUser + "&xAnneeStat=" + anneeSelected;
        // console.log(goToUrl);
        window.location.href = goToUrl;
    }
}

</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>