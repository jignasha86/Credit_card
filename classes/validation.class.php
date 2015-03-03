<?php
class CreditcardValidator {

   function __construct($data) {
       if(!defined('BASE_PATH'))
          define('BASE_PATH', realpath(__DIR__.'/../'));
       $this->data = $data;
   }

   private function checkSum($ccnum) {
		$checksum = 0;
		for ($i=(2-(strlen($ccnum) % 2)); $i<=strlen($ccnum); $i+=2) {
			$checksum += (int)($ccnum{$i-1});
		}
	  for ($i=(strlen($ccnum)% 2) + 1; $i<strlen($ccnum); $i+=2) {
	  		$digit = (int)($ccnum{$i-1}) * 2;
	  		if ($digit < 10) { 
	  			$checksum += $digit; 
	  		}
	  		else { 
	  			$checksum += ($digit-9); 
	  		}
    }
		if (($checksum % 10) == 0) 
			return true; 
		else 
			throw new Exception("Invalid creditcard number. Please check creditcard number");
	}

  private function isValidCardNumber() {
		$creditcard = array(  "visa"=>"/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/",
						"mastercard"=>"/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/",
						"discover"=>"/^6011-?\d{4}-?\d{4}-?\d{4}$/",
						"amex"=>"/^3[4,7]\d{13}$/",
						"diners"=>"/^3[0,6,8]\d{12}$/",
						"bankcard"=>"/^5610-?\d{4}-?\d{4}-?\d{4}$/",
						"jcb"=>"/^[3088|3096|3112|3158|3337|3528]\d{12}$/",
						"enroute"=>"/^[2014|2149]\d{11}$/",
						"switch"=>"/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");
		$match=false;
		foreach($creditcard as $type=>$pattern) {
			if(preg_match($pattern,$this->data['cardnumber']) === 1) {
				$match=true;
				$this->cardType = $type;
				break;
			}
		}
		if(!$match) {
			throw new Exception("Invalid card number. Please check creditcard number");			
		}
		else {
			return $this->checkSum($this->data['cardnumber']);
		}	
	}
  
  private function checkExpDate() {
		$expTs = mktime(0, 0, 0, $this->data['month'] + 1, 1, $this->data['year']);
		$curTs = time();
		$maxTs = $curTs + (10 * 365 * 24 * 60 * 60);
		if ($expTs > $curTs && $expTs < $maxTs) {
			return true;
		} else {
			throw new Exception("Invalid Expiry Month/Year");			
		}
	}
  
  private function validateCVV() {
		$count = ($this->cardType === 'amex') ? 4 : 3;
		if(preg_match('/^[0-9]{'.$count.'}$/', $this->data['cvv'])) {
			return true;
		} 
		else { 
		   throw new Exception("Invalid format of CVV number");		   
		} 
	}
  
  
  private function checkForEmpty() {
    $this->errorMessage = '';
    if($this->data['price'] == "") {
       $this->errorMessage .= 'Please enter price <br>';
    }
    if(isset($this->data['currency'])) {
      if($this->data['currency'] == "") {
         $this->errorMessage .= 'Please select currency <br>';
      }
    }
    if($this->data['full-name'] == "") { 
       $this->errorMessage .= 'Please enter customer full name <br>';
    }
		if($this->data['cardnumber'] == "") { 
       $this->errorMessage .= 'Please enter credit card number <br>';
    }
    if($this->data['month'] == "" || $this->data['year'] == "") {
       $this->errorMessage .= 'Please enter expiry date <br>';
    }                        
    if($this->data['cvv'] == "") {
       $this->errorMessage .= 'Please enter CVV number <br>';
    }
    
    $nameArr = explode(" ",$this->data['name']);
    if(sizeof($nameArr)!=2) {
      $this->errorMessage .= 'Please enter full card holder name <br>';  
    }
    
    if($this->data['month'] > 12 || $this->data['month'] < 0) {
       $this->errorMessage .= 'Please enter valid expiry month <br>'; 
    }
    
    if($this->errorMessage != "") {
      $arry['success'] = false;
    }
    else {
      $arry['success'] = true;
    }
    $arry['message'] = $this->errorMessage;
    return $arry;
    
    
	}
  
  
  function getResponse() {
  
      try {         
        $data = $this->checkForEmpty();
        if($data['success'] == false) {
           return $data;
        }
        $this->isValidCardNumber();
        $this->checkExpDate();
        $this->validateCVV();
      }
      catch(Exception $e) {
         $arry['success'] = false;
         $arry['message'] = $e->getMessage();
         return $arry;
      }
      
      $this->data['type'] = $this->cardType;
      
      if($this->cardType == 'amex' && $this->data['currency'] != 'usd') { 
         $arry['success'] = false;
         $arry['message'] = 'AMEX is possible to use with USD only';
         return $arry;
      }
      
      if($this->cardType == 'amex' && $this->data['currency'] == 'usd') {
         $method = 'paypal';
      }
      else if($this->data['currency'] == 'usd' || $this->data['currency'] == 'aud' || $this->data['currency'] == 'eur') {
         $method = 'paypal';
      }
      else {
        $method = 'braintreeM';
      }
      
      require_once BASE_PATH.'\config\\config.php';
      
      try { 
        $obj = new $method;      
        return $obj->processPayment($this->data);
      }
      catch(Exception $e) {
         $arry['success'] = false;
         $arry['message'] = $this->errorMessage;
         return $arry;
      }    
      
  }
  
  
}
?>