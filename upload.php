<?php require_once("includes/header.php");
require_once("includes/classes/Videodetailsformprovider.php");

 ?>
		<div class="column">
			<?php
				$formdetails=new Videodetailsformprovider($db);
				echo $formdetails->createUploadForm();



			?>
			
		</div>

<script>
	$("form").submit(function(){
		$("#exampleModalCenter").modal("show"); 
	})
</script>




<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-body">
        Please Wait...File Uploading
        <img src="assets/images/icons/loading-spinner.gif" >
      </div>
     
    </div>
  </div>
</div>
				
<?php require_once("includes/footer.php"); ?>