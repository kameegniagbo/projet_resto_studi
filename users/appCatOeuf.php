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



<div class="row">

	<div class="col-md-12">

	    <br>

	    <h3 >GESTION DES PRIX DES CATEGORIES D'OEUF </h3>

	    <hr>

	</div>

</div>

      	

<!-- #################################### form ############################### -->



<div class="row">



        <div class="col-md-4 mb-3">

            <div class="card border border-primary">    <!-- Card debut  -->

                <div class="card-header bg-primary text-white h6">NOUVEAUX PRIX</div>

                <div class="card-body table-responsive">

            

                    <input type="hidden" name="idUser" id="idUser" value="<?php echo $user->data()->id; ?>" readonly>


                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Date de vente</span> </div>

                        <input class="form-control form-control-sm" type="date" name="dateVente" id="dateVente" placeholder="Date de la Vente">

                    </div>
                    
                    
                    
<?php



//$user->data()->anneeStat

$query = $db->query("SELECT id, prix FROM appCatOeuf");

$results = $query->results(true);

$catOeufARRAY=array();

foreach ($results as $record)
{

array_push($catOeufARRAY, $record["id"]);
//echo $record["nom"]. "<br>";

?>

                            <div class="input-group mb-3 input-group-sm">
                                <div class="input-group-prepend"> <span class="input-group-text"><?php echo $record["id"]; ?></span> </div>
                                <input class="form-control form-control-sm" type="number" 
                                name="<?php echo $record["id"]; ?>" id="<?php echo $record["id"]; ?>" value="<?php echo $record["prix"]; ?>"
                                placeholder="Prix <?php echo $record["id"]; ?>">
                            </div>

<?php 

}

// dump($catOeufARRAY);

?>

                    <div class="alert alert-success text-center" id="resultSuccess" style="display:none;"></div>

                    <div class="alert alert-danger text-center" id="resultError" style="display:none;"></div>  
                    
                    <div class="alert alert-info text-left" id="resultAll" style="display:none;"></div>

                    <button type="button" class="form-control btn btn-outline-primary" id="insert">Valider</button>

                </div>


            </div>    <!-- Card fin  -->

        </div> 

            

        <div class="col-md-8 mb-3">

            <div class="card border border-dark">    <!-- Card debut  -->

                <div class="card-header bg-dark text-white h6">PRIX PAR CATEGORIE </div>

                <div class="card-body table-responsive">



                    <table id="tab" class="table table-hover" width="100%" cellspacing="0" style="font-size: 14px; color: black;">

                        <thead>

                            <tr>

                                <th>Date de vente</th>

                                <th>Categorie</th>

                                <th>Prix</th>

                                <th>Date</th>

                            </tr>

                        </thead>

                    </table>

            

                </div>

            </div>    <!-- Card fin  -->



        </div>



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

    $("#insert").click(function() {

        var dataToSend = new FormData();

        dataToSend.append('userLog', $("#idUser").val());
        dataToSend.append('dateVente', $("#dateVente").val());
        
        var catOeufARRAY = <?php echo json_encode($catOeufARRAY); ?>;
        catOeufARRAY.forEach(formBuild);
        
        function formBuild(item) {
          dataToSend.append(item, $("#"+item).val());
        }
        
        dataToSend.append('catOeufARRAY', catOeufARRAY);
        dataToSend.append('opera', 'updateData');
        
        // for (let [key, value] of dataToSend) { console.log(`${key}: ${value}`) }

        $.ajax({

          type: 'POST',

          url: "<?=$us_url_root?>users/api/catoeufReq",

          data: dataToSend,

          //cache: false,

          datatype: 'json',

          enctype: 'multipart/form-data',

          processData: false,

          contentType: false,

          timeout: 10000,

            success: function (data) {

            //   console.log(data.status);

                $("#resultAll").html(data);
    
                $("#resultAll").show();
    
                setTimeout(function(){ location.reload(true); }, 3000); 

            },

            error: function (request,error) {

                // This callback function will trigger on unsuccessful action

                alert('Problème de connexion, veuillez ressayer!' );
                // alert('Problème de connexion, veuillez ressayer!' + JSON.stringify(request) );
                // alert('Problème de connexion, veuillez ressayer!' + JSON.stringify(error) );

                //alert(error);

            }

          });

    });

    

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

    

    var postData = {

                    opera : "getAllData"

                  };

                  

	$('#tab').DataTable({

	        "responsive": true,

		    "order": [[ 1, "asc" ]],

		    "pageLength": 10,

            dom: 'Bfrtip',

            buttons: [

                // 'copyHtml5',

                'excelHtml5',

                // 'csvHtml5',

                // 'pdfHtml5',

                // 'print',

                'pageLength'

            ],

            lengthMenu: [

              [ 10, 25, 50, 100, -1 ],

              [ '10 rows', '25 rows', '50 rows', '100 rows', 'Show all' ]

            ],

            // "columnDefs": [

            //     {

            //         "targets": [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 18, 20, 22, 23, 24, 25, 26, 27, 28, 29  ],

            //         "visible": false,

            //         "searchable": true

            //     }

            // ],

		    "bProcessing": true,

		    "serverSide": true,

		    "ajax":{

				url : "<?=$us_url_root?>users/api/catoeufReq", // json datasource

				data : postData,

				type: "POST",  // type of method  ,GET/POST/DELETE

				error: function(){

					console.log("Error ! ");

				}

		    }

	});



});





</script>



<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>