<?php
// Connect to database
header('Access-Control-Allow-Origin: *');
require_once '../init.php';
require_once 'fieldsCheckingFunctions.php';
date_default_timezone_set('UTC');
$dateTimeNow = date('Y-m-d H:i:s');
$dateNow = date('Y-m-d');
$request_method = $_SERVER["REQUEST_METHOD"];
$table0 = "appSal";

function getAllData()
{
	global $appDBconn;
	global $table0;
	
	
	// initilize all variable
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
        0 => 'id',
        1 => 'nom',
        2 => 'nomBat',
        3 => 'nomRespo',
        4 => 'note',
        5 => 'datehr'
	);

	$where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" WHERE 1 AND ";
		$where .=" ( nom LIKE '%".$params['search']['value']."%' )";
	}

	// getting total number records without any search
	$sql = "SELECT * FROM ".$table0."  ";
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && $where != '') {

		$sqlTot .= $where;
		$sqlRec .= $where;
	}

 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

	$queryTot = mysqli_query($appDBconn, $sqlTot) or die("database error:". mysqli_error($appDBconn));

	$totalRecords = mysqli_num_rows($queryTot);

	$queryRecords = mysqli_query($appDBconn, $sqlRec) or die("error to fetch data");

	//iterate on results row and create new index array of data
    while( $row = mysqli_fetch_row($queryRecords) ) 
    { 
		$data[] = $row;
	}	

	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);  // send data as json format
}

// function getData($id=0)
// {
// 	global $appDBconn;
// 	global $table;
// 	$query = "SELECT * FROM ".$table;
// 	if($id != 0)
// 	{
// 		$query .= " WHERE id=".$id." LIMIT 1";
// 	}
// 	$response = array();
// 	$result = mysqli_query($appDBconn, $query);
// 	while($row = mysqli_fetch_assoc($result))
// 	{
// 		$response[] = $row;
// 	}
// 	header('Content-Type: application/json');
// 	echo json_encode($response, JSON_PRETTY_PRINT);
// }

function addData()
{

	global $appDBconn, $table0, $dateTimeNow, $dateNow;

    $nom = checkEmptyInput($_POST["nom"]);
    $nomBat = checkEmptyInput($_POST["nomBat"]);
    $nomRespo = checkEmptyInput($_POST["nomRespo"]);
    $note = inputSanitize($_POST["note"]);
    $datehr = $dateTimeNow;

        // $querySelect = "SELECT * FROM ".$table0." WHERE idUser='".$idUser."' AND annee='".$annee."' AND codActiv='".$codActiv."' ";	
        // $resultSelect = mysqli_query($appDBconn, $querySelect);
        
        // if( mysqli_num_rows($resultSelect) > 0) {
            
        //     $row = mysqli_fetch_assoc($resultSelect);
        //     $idx = $row["id"];
            
              
        //     $query1="UPDATE ".$table0." SET 
        //       respoExec='".$respoExec."', 
        //       cal='".$cal."', qte='".$qte."', ctUnit='".$ctUnit."', 
        //       ctBase='".$ctBase."', mntDispoETAT='".$mntDispoETAT."', mntDispoCOGES='".$mntDispoCOGES."', 
        //       mntDispoOMS='".$mntDispoOMS."', mntDispoUNICEF='".$mntDispoUNICEF."', mntDispoUNFPA='".$mntDispoUNFPA."', 
        //       mntDispoFM='".$mntDispoFM."', mntDispoBM='".$mntDispoBM."', mntDispoHDI='".$mntDispoHDI."', 
        //       mntDispoAUTRES='".$mntDispoAUTRES."', srcCtNonFin='".$srcCtNonFin."', dateTimeLog='".$dateTimeNow."' WHERE id='".$idx."' "; 
        
        //     if(mysqli_query($appDBconn, $query1))
        //     {
        //     $response=array(
        //     'status' => 1,
        //     'status_message' =>'Données mises à jour avec succès !'
        //     );
        //     }
        //     else
        //     {
        //     $response=array(
        //     'status' => 0,
        //     'status_message' =>'ERREUR!.'. mysqli_error($appDBconn)
        //     );
        //     }
        //     header('Content-Type: application/json');
        //     echo json_encode($response);
        // }
        // else
        // {
              $query0 = "INSERT INTO ".$table0." (nom, nomBat, nomRespo, note, datehr) 
                        VALUES('".$nom."', '".$nomBat."', '".$nomRespo."', '".$note."', '".$datehr."') ";
        
            if(mysqli_query($appDBconn, $query0))
            {
                $insert_id = mysqli_insert_id($appDBconn);
                $response=array(
                'status' => 1,
                'status_message' =>'Données ajoutées avec succès !' );
            }
            else
            {
                $response=array(
                'status' => 0,
                'status_message' =>'ERREUR! ->  '. mysqli_error($appDBconn) );
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        // }

}

// function updateData($id)
// {
// 	global $appDBconn;
// 	global $table;
// 	$_PUT = array();
// 	parse_str(file_get_contents('php://input'), $_PUT);
// 	$name = $_PUT["name"];
// 	$description = $_PUT["description"];
// 	$price = $_PUT["price"];
// 	$category = $_PUT["category"];
// 	$created = 'NULL';
// 	$modified = date('Y-m-d H:i:s');
// 	$query="UPDATE ".$table." SET name='".$name."', description='".$description."', price='".$price."', category_id='".$category."', modified='".$modified."' WHERE id=".$id;
	
// 	if(mysqli_query($appDBconn, $query))
// 	{
// 		$response=array(
// 			'status' => 1,
// 			'status_message' =>'Produit mis a jour avec succes.'
// 		);
// 	}
// 	else
// 	{
// 		$response=array(
// 			'status' => 0,
// 			'status_message' =>'Echec de la mise a jour de produit. '. mysqli_error($appDBconn)
// 		);
		
// 	}
	
// 	header('Content-Type: application/json');
// 	echo json_encode($response);
// }

// function deleteData($id)
// {
// 	global $appDBconn;
// 	global $table;
// 	$query = "DELETE FROM ".$table." WHERE id=".$id;
// 	if(mysqli_query($appDBconn, $query))
// 	{
// 		$response=array(
// 			'status' => 1,
// 			'status_message' =>'Produit supprime avec succes.'
// 		);
// 	}
// 	else
// 	{
// 		$response=array(
// 			'status' => 0,
// 			'status_message' =>'La suppression du produit a echoue. '. mysqli_error($appDBconn)
// 		);
// 	}
// 	header('Content-Type: application/json');
// 	echo json_encode($response);
// }

switch($request_method)
{


	case 'POST':
		// Retrive data
		if ($_POST["opera"] == 'getAllData')
		{
		    getAllData();
		}
		
		// Add new data
		if ($_POST["opera"] == 'addData')
		{
		    addData();
		}
		break;
		
	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>