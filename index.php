<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>Credit cards</title>
	<link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.form.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
      $body = $("body");
	    var options = { 
	        success: showResponse,
	        clearForm: false,
	        dataType : "json"
		  }; 
	    $('#creditcard').ajaxForm(options); 
	}); 
 	function showResponse(response, statusText, xhr, $form)  { 
    $body.removeClass("loading");
 		$(".validation-errors").html('');
 		if(response.success == true) {
 			$('.validation-errors').html("<div class='alert alert-success'>"+response.message+"</div>");
      //document.getElementById("creditcard").reset();
 		}
 		else{
 			$('.validation-errors').html("<div class='alert alert-danger'>"+response.message+"</div>");
 		}
	}
  
  function processButton() {
     $body.addClass("loading"); 
  }
   
    
	</script>

   </head>

  <body>

  <div class="container"> 
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
			<div class="validation-errors"></div> 
	  			<form role="form" method="post" action="validation.php" id ="creditcard" class="well">
              <h3 class="text-center">Order Section</h3>
		          <hr>
              
              <div class="form-group">
			          <label for="name">Price</label>
			          <input type="text" name="price" placeholder="Price" class="form-control form-price" id="price">
			        </div>
              
              <div class="row">
				      <div class="col-xs-8 col-sm-8 col-md-8">
              <div class="form-group">
			          <label for="currency">Currency</label>
			        </div>              
              </div>
              <div class="col-xs-8 col-sm-8 col-md-8">
              <div class="form-group">
              <select id="currency" class="form-control form-currency" name="currency">
                   <option value="usd">USD</option>
                   <option value="aud">AUD</option>
                   <option value="eur">EUR</option>
                   <option value="gbp">GBP</option>
              </select>
              </div>
              </div>
              </div>                  
              
			        <div class="form-group">
			          <label for="full-name">Full name</label>
			          <input type="text" name="full-name" placeholder="Full name" class="form-control" id="full-name">
			        </div>
              
              <hr>
              <hr>
              <h3 class="text-center">Payment Section</h3>
		          <hr>
              <hr>
              	
			        <div class="form-group">
			          <label for="name">Name on Card</label>
			          <input type="text" name="name" placeholder="Name on card" class="form-control" id="name">
			        </div>
			        <div class="form-group">
			          <label for="cardnumber">Card number</label>
			          <input type="text" name="cardnumber" placeholder="Like 4012888888881881" class="form-control" id="cardnumber">
			        </div>
			        <div class="row">
				        <div class="col-xs-8 col-sm-8 col-md-8">
				        	<label for="expiration">Expiry Date</label>
				        	<div class="row">
				        		<div class="col-xs-6 col-sm-6 col-md-6">				        			
				        			<div class="form-group">
							          <input type="text" name="month" size="2" maxlength="2" placeholder="MM" class="form-control" id="expiration">
							        </div>
				        		</div>
				        		<div class="col-xs-6 col-sm-6 col-md-6">
				        			<div class="form-group">
							          <input type="text" name="year" size="4" maxlength="4" placeholder="YYYY" class="form-control" id="year">
							        </div>
				        		</div>
				        	</div>

				        </div>
				         <div class="col-xs-4 col-sm-4 col-md-4">
				          	<div class="form-group">
					          <label for="cvv">CVV</label>
					          <input type="text" name="cvv" placeholder="CVV" size="4" maxlength="4" class="form-control" id="cvv">
					        </div>
				        </div>
				    </div>
			        <button type="submit" onclick="processButton()" class="btn btn btn-danger btn-block btn-lg">Pay Now</button>
			    </form>
          
          

	</div>
	</div>    
	</div>
  <div class="modal"></div>
  </body>
</html>