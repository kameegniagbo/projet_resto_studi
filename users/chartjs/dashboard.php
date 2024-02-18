<?php
require 'chartjs/src/ChartJS.php';
use ChartJs\ChartJS;
require_once("api/db_connect.php");

//#################################################################

$sql = "SELECT * FROM appLivretPay WHERE typPay='Don' ";
$result = mysqli_query($conn, $sql);
$totalPayDon = mysqli_num_rows($result);

$sql = "SELECT SUM(amount) AS totalPayDonSum FROM appLivretPay WHERE typPay='Don' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$totalPayDonSum = $row["totalPayDonSum"];

$sql = "SELECT * FROM appLivretPay WHERE typPay='Abonmt' ";
$result = mysqli_query($conn, $sql);
$totalPayAbonmt = mysqli_num_rows($result);

$sql = "SELECT SUM(amount) AS totalPayAbonmtSum FROM appLivretPay WHERE typPay='Abonmt' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$totalPayAbonmtSum = $row["totalPayAbonmtSum"];

$sql = "SELECT * FROM appPosts";
$result = mysqli_query($conn, $sql);
$totalLecture = mysqli_num_rows($result);

$sql = "SELECT * FROM appUsers";
$result = mysqli_query($conn, $sql);
$totalUsers = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Aneho' ";
$result = mysqli_query($conn, $sql);
$vill1 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Lome' ";
$result = mysqli_query($conn, $sql);
$vill2 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Tsevie' ";
$result = mysqli_query($conn, $sql);
$vill3 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Notse' ";
$result = mysqli_query($conn, $sql);
$vill4 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Atakpame' ";
$result = mysqli_query($conn, $sql);
$vill5 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Sokode' ";
$result = mysqli_query($conn, $sql);
$vill6 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Baffilo' ";
$result = mysqli_query($conn, $sql);
$vill7 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Kara' ";
$result = mysqli_query($conn, $sql);
$vill8 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Dapaong' ";
$result = mysqli_query($conn, $sql);
$vill9 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE villResid='Cinkasse' ";
$result = mysqli_query($conn, $sql);
$vill10 = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE sexe='Femme' ";
$result = mysqli_query($conn, $sql);
$pieFemme = mysqli_num_rows($result);

$sql = "SELECT * FROM appMeet WHERE sexe='Homme' ";
$result = mysqli_query($conn, $sql);
$pieHomme = mysqli_num_rows($result);

//#################################################################

$valuesBar = [ 
                [$vill1, $vill2, $vill3, $vill4, $vill5, $vill6, $vill7, $vill8, $vill9, $vill10]
          ];
          
$valuesPie = [ 
                [$pieFemme, $pieHomme]
          ];

//#################################################################

$dataBar = [
    'labels' => ["Aneho", "Lome", "Tsevie", "Notse", "Atakpame", "Baffilo", "Sokode", "Kara", "Dapaong", "Cinkasse"],
    'datasets' => [] //You can add datasets directly here or add them later with addDataset()
        ];
        
$dataPie = [
    'labels' => ["Femme", "Homme"],
    'datasets' => [] //You can add datasets directly here or add them later with addDataset()
        ];

//#################################################################

$attributesBar = ['id' => 'example', 'width' => 700, 'height' => 400, 'style' => 'display:inline;'];
$attributesPie = ['id' => 'example', 'width' => 400, 'height' => 400, 'style' => 'display:inline;'];

//#################################################################

$colors = [
              ['backgroundColor' => 'rgba(28,116,190,.8)', 'borderColor' => 'blue'],
              ['backgroundColor' => '#f2b21a', 'borderColor' => '#e5801d'],
              ['backgroundColor' => ['#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb', '#11f1eb']],
              ['backgroundColor' => ['yellow', 'purple']]
          ];

//There is a bug in Chart.js that ignores canvas width/height if responsive is not set to false
$options = ['responsive' => false];

$datasets = [
                ['data' => $valuesBar[0], 'label' => ""] + $colors[2],
                ['data' => $valuesPie[0]] + $colors[3],
                ['data' => $valuesBar[0], 'label' => "Legend1"] + $colors[1],
                ['data' => $valuesBar[1], 'label' => "Legend2"] + $colors[2],
                ['data' => $valuesBar[0]] + $colors[2],
            ];

//#################################################################

/*
 * Create charts
 *
*/

// $attributes['id'] = 'example_line';
// $Line = new ChartJS('line', $data, $options, $attributes);
// $Line->addDataset($datasets[0]);
// $Line->addDataset($datasets[1]);

$attributesBar['id'] = 'example_bar';
$Bar = new ChartJS('bar', $dataBar, $options, $attributesBar);
$Bar->addDataset($datasets[0]);
//$Bar->addDataset($datasets[3]);

// $attributes['id'] = 'example_radar';
// $Radar = new ChartJS('radar', $data, $options, $attributes);
// $Radar->addDataset($datasets[0]);
// $Radar->addDataset($datasets[1]);

// $attributes['id'] = 'example_polarArea';
// $PolarArea = new ChartJS('polarArea', $data, $options, $attributes);
// $PolarArea->addDataset($datasets[4]);

$attributesPie['id'] = 'example_pie';
$Pie = new ChartJS('pie', $dataPie, $options, $attributesPie);
$Pie->addDataset($datasets[1]);

// $attributes['id'] = 'example_doughnut';
// $Doughnut = new ChartJS('doughnut', $data, $options, $attributes);
// $Doughnut->addDataset($datasets[4]);


/*
 * Print charts
 *
 */

// echo $Line;

// echo $Bar;

// echo $Radar;

// echo $PolarArea;

// echo $Pie. $Doughnut;
?>