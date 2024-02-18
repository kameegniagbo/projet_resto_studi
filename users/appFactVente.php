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

	    <h3 >REGISTRE DES FACTURES DE VENTE</h3>

	    <hr>

	</div>

</div>

      	

<!-- #################################### form ############################### -->



<div class="row">
          

        <div class="col-md-12 mb-3">

            <div class="card border border-primary">    <!-- Card debut  -->

                <div class="card-header bg-primary text-white h6">LISTE DES FACTURES </div>

                <div class="card-body table-responsive">



                    <table id="tab" class="table table-hover" width="100%" cellspacing="0" style="font-size: 14px; color: black;">

                        <thead>

                            <tr>

                                <th width="5%">#ID</th>

                                <th width="10%">Date de la facture</th>

                                <th width="20%">Nom du client</th>

                                <th >Numero de la facture</th>

                                <th width="10%">Lieu</th>

                                <th width="15%">Note</th>

                                <th width="10%">Date</th>

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

        dataToSend.append('date', $("#date").val());

        dataToSend.append('nomClt', $("#nomClt").val());

        dataToSend.append('numFact', $("#numFact").val());

        dataToSend.append('lieu', $("#lieu").val());

        dataToSend.append('note', $("#note").val());

        dataToSend.append('opera', 'addData');

        $.ajax({

          type: 'POST',

          url: "<?=$us_url_root?>users/api/factVenteReq",

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

		    "order": [[ 0, "asc" ]],

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

				url : "<?=$us_url_root?>users/api/factVenteReq", // json datasource

				data : postData,

				type: "POST",  // type of method  ,GET/POST/DELETE

				error: function(){

					console.log("Error ! ");

				}

		    }

	});



});



//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------

function funcShowFactDetails(theNumFact) {

    // $("#btnFactDetail-"+theNumFact).removeClass("fa-cogs").addClass("fa-spinner fa-spin");

    // $("#btnFactDetail-"+theNumFact).click(function(){
    //     $("#divFactDetail-"+theNumFact).collapse('toggle');
    // });

    var dataToSend = new FormData();

        dataToSend.append('numFact', $("#showFactDetails").val());

        dataToSend.append('opera', 'showFactDetails');

        $.ajax({

          type: 'POST',

          url: "<?=$us_url_root?>users/api/factVenteReq",

          data: dataToSend,

          //cache: false,

          datatype: 'json',

          enctype: 'multipart/form-data',

          processData: false,

          contentType: false,

          timeout: 10000,

    

            success: function (data) {

              //console.log(data.status);

                    // $("#divFactDetail-"+theNumFact).html(data.status_message);

                    $("#divFactDetail-"+theNumFact).html(data);

                    // $("#divFactDetail-"+theNumFact).show();

                    $("#divFactDetail-"+theNumFact).collapse('toggle');

                    // setTimeout(function(){ location.reload(true); }, 2000); 

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

</script>



<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>