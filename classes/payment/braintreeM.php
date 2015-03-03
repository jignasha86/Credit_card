<?php

require_once BASE_PATH.'\library\\braintree\\lib\\Braintree.php';

class braintreeM {

   /**
    * Constructor
    * Sets sandbox environment and have to use provided library
    */


   public function __construct() {
   
      Braintree_Configuration::environment('sandbox');
      Braintree_Configuration::merchantId(MERCHANT_ID);
      Braintree_Configuration::publicKey(PUBLIC_KEY);
      Braintree_Configuration::privateKey(PRIVATE_KEY);
   
   }
   
   
   public function processPayment($data) {
   
      $nameArr = explode(" ",$data['name']);
      $firstName = "";$lastName = "";
      if(sizeof($nameArr)==2) {
        $firstName = $nameArr[0];
        $lastName = $nameArr[1]; 
      }
      else {
        $firstName = $data['name'];
        $lastName = ""; 
      }
   
      $data1 = array(
          'amount' => $data['price'],
          'merchantAccountId' => MERCHANT_ID,
          'creditCard' => array(
            'number' => $data['cardnumber'],
            'expirationDate' => $data['month']."/".$data['year'],
            'cardholderName' => $data['name'],
            'cvv' => $data['cvv']
          ),
          'customer' => array(
            'firstName' => $firstName,
            'lastName' => $lastName,
          ),
          'options' => array(
            'submitForSettlement' => true
          )
      );
      
      $result = Braintree_Transaction::sale($data1);
      //print_r($result);
      
      $arry = array();
      
      if ($result->success) {
          $arry['success'] = true;
          $arry['message'] = "Braintree payment saved";
          $data['id'] = $result->transaction->id;
          $data['method'] = 'braintree';
          db::save_order($data); 
      } else if ($result->transaction) {
          $arry['success'] = false;
          $arry['message'] = "Error-braintree ".$result->message; 
      } 
      else {
          foreach (($result->errors->deepAll()) as $error) {
              $arry['message'] = "Error-braintree - ".$error->message."<br/>";
              $arry['success'] = false;
          }
      }
      
      
      return $arry;
   
   }


}
?>