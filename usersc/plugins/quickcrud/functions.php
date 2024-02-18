<?php
if(!function_exists('quickCrud')) {
  function quickCrud($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalNewData">
                 <i class="fa fa-plus"></i> Ajouter
              </button>
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editable" id="paginate">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update"
                        <?php if($k == "id"){echo "class='uneditable'";}?> 
                          ><?=$v?></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
         $('.editable').DataTable({"pageLength": 25,"stateSave": true,"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, 250, 500]], "aaSorting": []});
        });
      </script>
      <script type="text/javascript">
        $('.editable').editableTableWidget();
        $('#editable td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editable td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });
      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}
//################################################################################################################################# livrets

if(!function_exists('quickCrudTextLivret')) {
  function quickCrudTextLivret($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id_post)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalNewData">
                 <i class="fa fa-plus"></i> Ajouter
              </button>
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editable" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id_post;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                         <?php if($k == "id_post"){echo "class='uneditable' style='width: 5%'";}?> 
                         <?php if($k == "date_text"){echo "style='width: 15%'";}?> 
                         <?php if($k == "numLivret"){echo "style='width: 5%'";}?>
                         <?php if($k == "moisAnnLivret"){echo "style='width: 10%'";}?>
                         <?php if($k == "textSemTempsLiturg"){echo "style='width: 20%'";}?>
                         <?php if($k == "nom_saint"){echo "style='width: 20%'";}?>
                         <?php if($k == "ref_evang"){echo "style='width: 10%'";}?>
                         <?php if($k == "text_post"){echo "style='width: 5%'";}?> 
                         <?php if($k == "img_post"){echo "style='width: 10%'";}?> 
                         >
                        <?php
                        if($k == "id_post"){echo $v;}
                        if($k == "date_text"){echo $v;}
                        if($k == "numLivret"){echo $v;}
                        if($k == "moisAnnLivret"){echo $v;}
                        if($k == "textSemTempsLiturg"){echo $v;}
                        if($k == "nom_saint"){echo $v;}
                        if($k == "ref_evang"){echo $v;}
                        if($k == "img_post"){echo "<img src='$v' width='100%'>";}
                        if($k == "text_post")
                            {?>
                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#myModal<?php echo $id; ?>">
                          Afficher
                        </button>
                        
                        <!-- The Modal -->
                        <div class="modal" id="myModal<?php echo $id; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                        
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                        
                              <!-- Modal body -->
                              <div class="modal-body">
                                <?php echo $v; ?>
                              </div>
                        
                              <!-- Modal footer -->
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                              </div>
                        
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id_post?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id_post?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
         $('.editable').DataTable({
                      "order": [[ 0, "desc" ]]
        } );
        });
      </script>
      <script type="text/javascript">
        $('.editable').editableTableWidget();
        $('#editable td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editable td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//########################################################################################################################## PayGate

if(!function_exists('quickCrudPayGate')) {
  function quickCrudPayGate($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editablepaygate" id="paginate" style="font-size: 10px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?>
                        <?php if($k == "username"){echo "style='width: 10%'";}?>
                        <?php if($k == "nom"){echo "style='width: 20%'";}?>
                        <?php if($k == "payment_reference"){echo "style='width: 10%'";}?>
                        <?php if($k == "datetime"){echo "style='width: 5%'";}?>
                        <?php if($k == "identifier"){echo "style='width: 15%'";}?>
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
              $('.editablepaygate').DataTable({
                            "order": [[ 0, "desc" ]]
              } );
        });
      </script>
      <script type="text/javascript">

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//########################################################################################################################## ACL Premium

if(!function_exists('quickCrudPremium')) {
  function quickCrudPremium($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalNewData">
                 <i class="fa fa-plus"></i> Ajouter
              </button>
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editablepremium" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?> 
                        <?php if($k == "username"){echo "style='width: 15%'";}?> 
                        <?php if($k == "nom"){echo "style='width: 20%'";}?> 
                        <?php if($k == "typACL"){echo "style='width: 10%'";}?> 
                        <?php if($k == "numLivret"){echo "style='word-break: break-all;'";}?> 
                        <?php if($k == "logDateTime"){echo "style='width: 10%'";}?>
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
            $('.editablepremium').DataTable({
                          "order": [[ 0, "desc" ]]
            } );
        });
      </script>
      <script type="text/javascript">
        $('.editablepremium').editableTableWidget();
        $('#editablepremium td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editablepremium td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}


//########################################################################################################################## ACL Premium

if(!function_exists('quickCrudNormalACL')) {
  function quickCrudNormalACL($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editablenormal" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?> 
                        <?php if($k == "username"){echo "style='width: 15%'";}?> 
                        <?php if($k == "nom"){echo "style='width: 20%'";}?> 
                        <?php if($k == "typACL"){echo "style='width: 10%'";}?> 
                        <?php if($k == "numLivret"){echo "style='word-break: break-all;'";}?> 
                        <?php if($k == "logDateTime"){echo "style='width: 10%'";}?> 
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
            $('.editablenormal').DataTable({
                          "order": [[ 0, "desc" ]]
            } );
        });
      </script>
      <script type="text/javascript">
        $('.editablenormal').editableTableWidget();
        $('#editablenormal td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editablenormal td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//################################################################################################################################# livrets

if(!function_exists('quickCrudLivret')) {
  function quickCrudLivret($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalNewData">
                 <i class="fa fa-plus"></i> Ajouter
              </button>
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editable" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                         <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?> 
                         <?php if($k == "numLivret"){echo "style='width: 5%'";}?> 
                         <?php if($k == "moisAnnLivret"){echo "style='width: 5%'";}?> 
                         <?php if($k == "textMoisAnnLivret"){echo "style='width: 10%'";}?> 
                         <?php if($k == "textLivret"){echo "style='width: 30%'";}?> 
                         <?php if($k == "imgLivret"){echo "style='width: 10%'";}?> 
                         <?php if($k == "logDateTime"){echo "style='width: 10%'";}?>
                         >
                        <?php
                        if($k == "id"){echo $v;}
                        if($k == "numLivret"){echo $v;}
                        if($k == "moisAnnLivret"){echo $v;}
                        if($k == "textMoisAnnLivret"){echo $v;}
                        if($k == "textLivret"){echo $v;}
                        if($k == "imgLivret"){echo "<img src='$v' width='100%'>";}
                        if($k == "logDateTime"){echo $v;}
                        ?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id_post?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id_post?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
            $('.editable').DataTable({
                          "order": [[ 0, "desc" ]]
            } );
        });
      </script>
      <script type="text/javascript">
        $('.editable').editableTableWidget();
        $('#editable td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editable td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//################################################################################################################################# sliders

if(!function_exists('quickCrudSliders')) {
  function quickCrudSliders($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
              <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalNewData">
                 <i class="fa fa-plus"></i> Ajouter
              </button>
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editable" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if($k == "img1"){echo "style='width: 20%'";}?> 
                         <?php if($k == "img2"){echo "style='width: 20%'";}?> 
                         <?php if($k == "img3"){echo "style='width: 20%'";}?> 
                         <?php if($k == "img4"){echo "style='width: 20%'";}?> 
                         <?php if($k == "img5"){echo "style='width: 20%'";}?> 
                         >
                        <?php
                        if($k == "img1"){echo "<img src='$v' width='100%'>";}
                        if($k == "img2"){echo "<img src='$v' width='100%'>";}
                        if($k == "img3"){echo "<img src='$v' width='100%'>";}
                        if($k == "img4"){echo "<img src='$v' width='100%'>";}
                        if($k == "img5"){echo "<img src='$v' width='100%'>";}
                        ?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id_post?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id_post?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
         $('.editable').DataTable();
        });
      </script>
      <script type="text/javascript">

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//################################################################################################################################# change phone

if(!function_exists('quickCrudChangePhone')) {
  function quickCrudChangePhone($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste

          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editabledmdchphone" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?> 
                        <?php if($k == "username"){echo "style='width: 10%'";}?> 
                        <?php if($k == "nom"){echo "style='width: 20%'";}?> 
                        <?php if($k == "phoneID"){echo "style='width: 15%'";}?> 
                        <?php if($k == "password"){echo "style='width: 15%'";}?> 
                        <?php if($k == "statut"){echo "style='width: 15%'";}?> 
                        <?php if($k == "logDateTime"){echo "style='width: 20%'";}?> 
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
              $('.editabledmdchphone').DataTable({
                            "order": [[ 0, "desc" ]]
              } );
        });
      </script>
      <script type="text/javascript">
        $('.editabledmdchphone').editableTableWidget();
        $('#editabledmdchphone td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editabledmdchphone td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//################################################################################################################################# change profil data

if(!function_exists('quickCrudChangeProfilData')) {
  function quickCrudChangeProfilData($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste

          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editabledmdchprofil" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                         <?php if(hasPerm([3],$user->data()->id)){ echo "class='uneditable'"; }; ?>
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?> 
                        <?php if($k == "username"){echo "style='width: 20%'";}?> 
                        <?php if($k == "motif"){echo "style='width: 25%'";}?> 
                        <?php if($k == "newVal"){echo "style='width: 25%'";}?> 
                        <?php if($k == "statut"){echo "style='width: 15%'";}?> 
                        <?php if($k == "logDateTime"){echo "style='width: 10%'";}?> 
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
            $('.editabledmdchprofil').DataTable({
                          "order": [[ 0, "desc" ]]
            } );
        });
      </script>
      <script type="text/javascript">
        $('.editabledmdchprofil').editableTableWidget();
        $('#editabledmdchprofil td.uneditable').on('change', function(evt, newValue) {
          	return false;
          });
          $('.editabledmdchprofil td').on('change', function(evt, newValue) {

        	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
            value: newValue,
            key: $(this).attr("data-key"),
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
           })
        		.done(function( data ) {
            			if(data != ""){alert(data);}
        		});
        	;
        });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}


//################################################################################################################################# list Media

if(!function_exists('quickCrudMediaLink')) {
  function quickCrudMediaLink($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste

          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editable" id="paginate" style="font-size: 12px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update"
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?> 
                        <?php if($k == "name"){echo "style='width: 20%'";}?> 
                        <?php if($k == "size"){echo "style='width: 10%'";}?> 
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
         $('.editable').DataTable();
        });
      </script>
      <script type="text/javascript">
        // $('.editable').editableTableWidget();
        // $('#editable td.uneditable').on('change', function(evt, newValue) {
        //   	return false;
        //   });
        //   $('.editable td').on('change', function(evt, newValue) {

        // 	$.post( "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php", {
        //     value: newValue,
        //     key: $(this).attr("data-key"),
        //     row: $(this).attr("data-row"),
        //     method: $(this).attr("data-method"),
        //     table: "<?=$table?>"
        //    })
        // 		.done(function( data ) {
        //     			if(data != ""){alert(data);}
        // 		});
        // 	;
        // });

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}

//########################################################################################################################## cinetpay

if(!function_exists('quickCrudCinetPay')) {
  function quickCrudCinetPay($query,$table, $opts = []){
    global $db,$user,$abs_us_root,$us_url_root,$formNumber;
    if(hasPerm([2,3],$user->data()->id)){
    if(!isset($formNumber) || $formNumber == ""){
      $formNumber = 0;
    }else{
      $formNumber = $formNumber + 1;
    }

    if(!isset($opts['class'])) {$opts['class'] = "table table-striped  table-sm paginate"; }
    if(!isset($opts['thead'])) {$opts['thead'] = ""; }
    if(!isset($opts['tbody'])) {$opts['tbody'] = ""; }

    if(!isset($opts['keys']) && $query != []){
      foreach($query['0'] as $k=>$v){
        $opts['keys'][] = $k;
      }
    }
    if($query != []){
      $row = "";
      ?>

      <!-- Card debut  -->
      <div class="card">
          <div class="card-header">
            Liste
          </div>
          <div class="card-body">

              <table class="<?=$opts['class']?> editablecinetpay" id="paginate" style="font-size: 10px;">
                <thead class="<?=$opts['thead']?>">
                  <tr>
        
                    <?php foreach($opts['keys'] as $k){?>
                      <th><?php echo $k;?></th>
                    <?php } ?>
                    <?php if(!isset($opts['nodupe'])){?>
                      <th>Duplicate</th>
                    <?php } ?>
                    <?php if(!isset($opts['nodel'])){?>
                      <th>Delete</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody class="<?=$opts['tbody']?>">
                  <?php foreach($query as $r){
                    $id = $r->id;
                    $row = $r;
                    ?>
                    <tr>
                      <?php foreach($r as $k=>$v){ ?>
                        <td
                         data-key="<?=$k?>" data-row="<?=$id?>" data-method="update" 
                        <?php if($k == "id"){echo "class='uneditable' style='width: 5%'";}?>
                        <?php if($k == "username"){echo "style='width: 10%'";}?>
                        <?php if($k == "nom"){echo "style='width: 20%'";}?>
                        <?php if($k == "payment_reference"){echo "style='width: 10%'";}?>
                        <?php if($k == "datetime"){echo "style='width: 5%'";}?>
                        <?php if($k == "identifier"){echo "style='width: 15%'";}?>
                          >
                            <?=$v?>
                        </td>
                      <?php } ?>
                      <?php if(!isset($opts['nodupe'])){?>
                        <td><button type="button" name="dupe" class="btn btn-primary trigger"
                            data-row="<?=$id?>" data-method="duplicate"
                            >Duplicate</button></td>
                      <?php } ?>
                      <?php if(!isset($opts['nodel'])){?>
                        <td><button type="button" name="del" class="btn btn-danger btn-sm trigger"
                            data-row="<?=$id?>" data-method="delete"
                            ><span class="glyphicon glyphicon-remove-sign" style="font-size: 26px;"></span></button></td>
                      <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
      
        </div>
    </div>
    <!-- Card fin  -->
     
      <script src="<?=$us_url_root?>usersc/plugins/quickcrud/assets/editable.js"></script>
      <script type="text/javascript" src="<?=$us_url_root?>users/js/pagination/datatables.min.js"></script>
      <script>
      $(document).ready(function () {
              $('.editablecinetpay').DataTable({
                            "order": [[ 0, "desc" ]]
              } );
        });
      </script>
      <script type="text/javascript">

        $(".trigger").click(function(data) {

          var formData = {
            row: $(this).attr("data-row"),
            method: $(this).attr("data-method"),
            table: "<?=$table?>"
          };
          $.ajax({
            type 		: 'POST',
            url 		: "<?=$us_url_root?>usersc/plugins/quickcrud/assets/parser.php",
            data 		: formData,
            dataType 	: 'json',
            encode 		: true
          })
          .done(function(data) {
            if(data.reload == true){
              location.reload(true);
            }
            if(data.msg != ""){
              alert(data.msg);
            }
          })
        });

      </script>
      <?php
    }else{
      ?>
      <p style="color:red;">Aucune donnée trouvée</p>
      <?php
    }
   }
  }
}
