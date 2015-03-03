<?php
class validationTest extends PHPUnit_Framework_TestCase {

   public function testBraintree() {
  
      $_POST['price'] = 11;
      $_POST['currency'] = 'gbp';
      $_POST['full-name'] = "Test Test";
      $_POST['name'] = "Test Test";
      $_POST['cardnumber'] = "4111111111111111";
      $_POST['month'] = 12;
      $_POST['year'] = 2016;
      $_POST['cvv'] = 124;
      
      define('ROOT_PATH', realpath(__DIR__.'/../'));
      require_once(ROOT_PATH."\\classes\\validation.class.php");
      
      spl_autoload_register(function ($class) {
             if($class == 'paypal' || $class == 'braintreeM') 
                require_once ROOT_PATH.'\classes\\payment\\' . $class . '.php';
      });
    
      $cvalidate = new CreditcardValidator($_POST);
      
      
	    $res = json_encode($cvalidate->getResponse());
      
      $this->assertNotNull($res);
      if (!is_object(json_decode($res))) {
          $this->assertFalse(true);
      } 
       
   }
   
   
   public function testPaypal() {
      
          $_POST['price'] = 11;
          $_POST['currency'] = 'usd';
          $_POST['full-name'] = "Test1 Test1";
          $_POST['name'] = "Test1 Test1";
          $_POST['cardnumber'] = "4417119669820331";
          $_POST['month'] = 12;
          $_POST['year'] = 2016;
          $_POST['cvv'] = 124;
          
          $cvalidate = new CreditcardValidator($_POST);
    	    $res = json_encode($cvalidate->getResponse());
          
          $this->assertNotNull($res);
          if (!is_object(json_decode($res))) {
              $this->assertFalse(true);
          } 
           
     }
   
  
   
}
?>