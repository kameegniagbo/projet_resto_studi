<?php
// Connect to database
header('Access-Control-Allow-Origin: *');
require_once '../init.php';
require_once 'fieldsCheckingFunctions.php';
date_default_timezone_set('UTC');
$dateTimeNow = date('Y-m-d H:i:s');
$dateNow = date('Y-m-d');
$request_method = $_SERVER["REQUEST_METHOD"];
$table0 = "appCatOeuf";
$table1 = "appCatOeufLog";
$responseUPDATE=array();

//////////////////////////////////////////////////////////////////////////////////////

function getAllData()
{
	global $appDBconn;
	global $table0;
	
	
	// initilize all variable
	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	//define index of column
	$columns = array( 
        0 => 'dateVente',
        1 => 'nom',
        2 => 'qte',
        3 => 'datehr'
	);

	$where = $sqlTot = $sqlRec = "";

	// check search value exist
	if( !empty($params['search']['value']) ) {   
		$where .=" WHERE 1 AND ";
		$where .=" ( nom LIKE '%".$params['search']['value']."%' )";
	}

	// getting total number records without any search
	$sql = "SELECT dateVente, nom, qte, datehr FROM ".$table0."  ";
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

//////////////////////////////////////////////////////////////////////////////////////

// function addData()
// {

// 	global $appDBconn, $table0, $dateTimeNow, $dateNow;

//     $dateVente = checkEmptyInput($_POST["dateVente"]);
//     $nom = checkEmptyInput($_POST["nom"]);
//     $prix = checkEmptyInput($_POST["prix"]);
//     $note = inputSanitize($_POST["note"]);
//     $datehr = $dateTimeNow;


//             $query0 = "INSERT INTO ".$table0." (dateVente, nom, prix, note, datehr) 
//                         VALUES('".$dateVente."', '".$nom."', '".$prix."', '".$note."', '".$datehr."') ";
                        
//             $query1 = "INSERT INTO ".$table1." (dateVente, nom, prix, note, datehr) 
//                         VALUES('".$dateVente."', '".$nom."', '".$prix."', '".$note."', '".$datehr."') ";
                        
//             if(mysqli_query($appDBconn, $query0))
//             {
//                 $insert_id = mysqli_insert_id($appDBconn);
//                 mysqli_query($appDBconn, $query1);
//                 $response=array(
//                 'status' => 1,
//                 'status_message' =>'Données ajoutées avec succès !' );
                
//             }
//             else
//             {
//                 $response=array(
//                 'status' => 0,
//                 'status_message' =>'ERREUR! ->  '. mysqli_error($appDBconn) );
//             }
            
//             header('Content-Type: application/json');
//             echo json_encode($response);
            

// }

//////////////////////////////////////////////////////////////////////////////////////

function updateData($xqte, $xnom)
{
	global $appDBconn, $table0, $table1, $dateTimeNow, $responseUPDATE;
	
    $dateVente = checkEmptyInput($_POST["dateVente"]);
    $datehr = $dateTimeNow;
	
	$query0="UPDATE ".$table0." SET qte=".checkEmptyInput($xqte).", dateVente='".$dateVente."', datehr='".$datehr."' WHERE id='".$xnom."' ";
	
	$query1 = "INSERT INTO ".$table1." (dateVente, nom, qte, datehr) 
                        VALUES('".$dateVente."', '".$xnom."', '".$xqte."', '".$datehr."') ";
	
	if(mysqli_query($appDBconn, $query0))
	{
        mysqli_query($appDBconn, $query1);

		$responseX=$xnom. ' : Chargement mis a jour avec succes. <br>';
	}
	else
	{
		$responseX=$xnom. ' : <b class="text-danger">Echec de la mise à jour de Chargement. Veuillez signaler cette erreur ! </b>'. mysqli_error($appDBconn).'<br>';
	}
	
	array_push($responseUPDATE, $responseX);
}

//////////////////////////////////////////////////////////////////////////////////////

function updateDataAll()
{
	
	global $responseUPDATE;
    
    $catOeufARRAY = $_POST["catOeufARRAY"];
    $catOeufARRAY = explode(',', $catOeufARRAY );
    
    foreach ($catOeufARRAY as $key => $val ) 
    {
        updateData($_POST[$val], $val);
    }
    
    header('Content-Type: application/json');
    echo json_encode($responseUPDATE);
}

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

		// update data
		if ($_POST["opera"] == 'updateData')
		{
		    updateDataAll();
		}
	break;
		
	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
	break;
}
?>