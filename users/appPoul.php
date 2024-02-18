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

	    <h3 >GESTION DES LOTS DE POUSSINS </h3>

      

	    <hr>

	</div>

</div>

      	

<!-- #################################### form ############################### -->



<div class="row">



        <div class="col-md-4 mb-3">

            <div class="card border border-primary">    <!-- Card debut  -->

                <div class="card-header bg-primary text-white h6">NOUVEL ENREGISTREMENT</div>

                <div class="card-body table-responsive">

            
                    <input type="hidden" name="idUser" id="idUser" value="<?php echo $user->data()->id; ?>" readonly>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Type d'opération </span> </div>

                        <select class="form-control form-control-sm" name="typOpera" id="typOpera">
                            <option value="">Veuillez selectionner</option>
                            <option value="OUT-OLD">[Sortie] Viellesse - Vente </option>
                            <option value="OUT-SICKNESS">[Sortie] Maladie - Quarantaine </option>
                            <option value="OUT-DEAD">[Sortie] Mortalité </option>
                            <option value="IN-NEW">[Entrée] Reception de Lot </option>
                        </select>
                    </div>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Date</span> </div>

                        <input class="form-control form-control-sm" type="date" name="date" id="date" placeholder="Date">

                    </div>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Lot</span> </div>

                        <input class="form-control form-control-sm" type="text" name="nomLot" id="nomLot" placeholder="Nom/Identifiant du lot" value="LOT-POUSSINS-<?php echo date("d-M-Y"); ?>">

                    </div>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Quantité </span> </div>

                        <input class="form-control form-control-sm" type="number" name="qtePoul" id="qtePoul" placeholder="Quantite de poussins">

                    </div>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Type de poussins</span> </div>

                        <input class="form-control form-control-sm" type="text" name="typPoul" id="typPoul" placeholder="Type de poussins">

                    </div>

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Salle</span> </div>

                        <select class="form-control form-control-sm" name="nomSal" id="nomSal" >

                            <option>Selectionnez une salle</option>

<?php



//$user->data()->anneeStat

$query = $db->query("SELECT nom FROM appSal");

$results = $query->results(true);



foreach ($results as $record)

{

//echo $record["nom"]. "<br>";

?>

                            <option value="<?php echo $record["nom"]; ?>"><?php echo $record["nom"]; ?></option>



<?php 

}

?>

                        </select>

                    </div>


                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Bâtiment</span> </div>

                        <select class="form-control form-control-sm" name="nomBat" id="nomBat" >

                            <option>Selectionnez un bâtiment</option>

<?php



//$user->data()->anneeStat

$query = $db->query("SELECT nom FROM appBat");

$results = $query->results(true);



foreach ($results as $record)

{

//echo $record["nom"]. "<br>";

?>

                            <option value="<?php echo $record["nom"]; ?>"><?php echo $record["nom"]; ?></option>



<?php 

}

?>

                        </select>

                    </div>

                    

                    <div class="input-group mb-3 input-group-sm">

                        <div class="input-group-prepend"> <span class="input-group-text">Note</span> </div>

                        <textarea class="form-control form-control-sm" type="text" name="note" id="note" placeholder="Note / Observation"></textarea>

                    </div>

                    

                    <div class="alert alert-success text-center" id="resultSuccess" style="display:none;"></div>

                    <div class="alert alert-danger text-center" id="resultError" style="display:none;"></div>  

                    <button type="button" class="form-control btn btn-outline-primary" id="insert">Ajouter</button>

                </div>

            </div>    <!-- Card fin  -->

        </div> 

            

        <div class="col-md-8 mb-3">

            <div class="card border border-dark">    <!-- Card debut  -->

                <div class="card-header bg-dark text-white h6">REGISTRE DES LOTS DE POUSSINS</div>

                <div class="card-body table-responsive">



                    <table id="tab" class="table table-hover" width="100%" cellspacing="0" style="font-size: 11px; color: black;">

                        <thead>

                            <tr>

                                <th>#ID</th>

                                <th>Type d'operation</th>

                                <th>Date opération</th>

                                <th>Lot</th>

                                <th>Quantité </th>
                                
                                <th>Type poussins</th>

                                <th>Salle</th>

                                <th>Batiment</th>

                                <th>Note</th>

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

        dataToSend.append('typOpera', $("#typOpera  option:selected").val());

        dataToSend.append('date', $("#date").val());

        dataToSend.append('nomLot', $("#nomLot").val());

        dataToSend.append('qtePoul', $("#qtePoul").val());

        dataToSend.append('typPoul', $("#typPoul").val());

        dataToSend.append('nomSal', $("#nomSal").val());

        dataToSend.append('nomBat', $("#nomBat").val());

        dataToSend.append('note',  $("#typOpera  option:selected").text() + $("#note").val());

        dataToSend.append('opera', 'addData');

        $.ajax({

          type: 'POST',

          url: "<?=$us_url_root?>users/api/poulReq",

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

                    $("#resultSuccess").html(data.status_message);

                    $("#resultSuccess").show();

                    setTimeout(function(){ location.reload(true); }, 2000); 

              }

              else

              {

                    $("#resultError").html(data.status_message);

                    $("#resultError").show();

              }

            },

            error: function (request,error) {

                // This callback function will trigger on unsuccessful action

                alert('Problème de connexion, veuillez ressayer!');

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

		    "order": [[ 0, "desc" ]],

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

				url : "<?=$us_url_root?>users/api/poulReq", // json datasource

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