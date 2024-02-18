<?php

////////////////////////////////////////////////

function inputSanitize($data) 
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = addslashes($data);
  return $data;
}

////////////////////////////////////////////////

function checkEmptyInput($data) 
{
	if (strlen($data) == 0 || $data == '' || is_null($data) ) 
	{
		$response=array(
            'status' => 0,
            'status_message' =>'Veuillez remplir les champs obligatoires et réessayez...!'
            );
        
        header('Content-Type: application/json');
        echo json_encode($response);
        
		exit;
	}
	else 
	{
		$data =  inputSanitize($data);
		return $data;
	}
}

////////////////////////////////////////////////

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

////////////////////////////////////////////////

function checkEmail($data) 
{
	if (!filter_var($data, FILTER_VALIDATE_EMAIL)) 
	{
	echo "
	<div class=\"alert alert-danger alert-dismissable\">
		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">×</a>
	  	<strong>Erreur!</strong>  Veuillez corriger votre email et reessayez.
	</div>
	";
	exit;
	} 
}

////////////////////////////////////////////////

function checkNumber($data) 
{
	if (!preg_match("/^[0-9]+$/i", $data)) 
	{
	echo "
		<div class=\"alert alert-danger alert-dismissable\">
			<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">×</a>
		  	<strong>Erreur!</strong>  Veuillez ecrire correctemenet le numero de telephone.<br>
		  	Exemple: 0022891911919
		</div>
		";
	exit;
	}  
}

////////////////////////////////////////////////

function qteToTonn($data) 
{
	if (strpos($data, '.'))
	{
	  $data = ROUND($data,3);
	  $data = number_format($data,3,"."," ");
	  echo $data; 
	}
	  
	if (!strpos($data, '.'))
	{
	  $data = number_format($data,0,"."," ");
	  echo $data;
	}
}

////////////////////////////////////////////////

function priceFormatToCFA($data) 
{
  echo number_format($data,0,"."," ");
}

////////////////////////////////////////////////

function priceFormatToEURO($data) 
{
  echo number_format(ROUND($data,2),2,"."," ");
}

////////////////////////////////////////////////

// function convertMonthYearToDateMonth($data) 
// {
// setlocale(LC_TIME, 'fr_FR');
// $data = explode("-", $data);
// $data = $data[1];
// echo utf8_encode(strftime("%B", mktime(null, null, null, $data, 1)))." ";
// }

////////////////////////////////////////////////

function convertMonthYearToDateYear($data) 
{
setlocale(LC_TIME, 'fr_FR');
$data = explode("-", $data);
echo $data[0];
}

////////////////////////////////////////////////

function getDBSuffix($data) 
{
$data = substr_replace($data,"",0,2);
$data = substr_replace($data,"",3,2);
$data = str_replace("/","",$data);
return $data;
}

?>