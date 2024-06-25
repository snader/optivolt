<!-- form start -->


  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">

  
          <h1 class="m-0"><i aria-hidden="true" class="fa fa-star fa-th-star"></i>&nbsp;&nbsp;Evaluatie  
          <?php 
            if (!empty($oEvaluation->customerId)) {
              
              echo _e("- " . $oEvaluation->getCustomer()->companyName); 
              ?>
              
              <?php
            
            } ?>
          </h1>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">

    <div class="row">
      
      <!-- left column -->
      <div class="col-md-6">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Evaluatieformulier verzonden</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
        
            Hartelijk dank voor het invullen en verzenden van het evaluatieformulier.<br><i>Thank you for sending the evaluationform.</i>
            
            
          </div>

        </div>

      </div>
 
   
    </div>
  </div>
</form>

