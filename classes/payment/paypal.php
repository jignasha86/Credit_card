<?php
class paypal {

    private $access_token;
    private $token_type;
    
    
    /**
    * Constructor
    *
    * Handles oauth 2 bearer token fetch
    * @link https://developer.paypal.com/webapps/developer/docs/api/#authentication--headers
    */
    
    public function __construct() {
      
        $ch = curl_init();
        $uri = URI_SANDBOX . "oauth2/token";

        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_USERPWD, CLIENT_ID.":".CLIENT_SECRET);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        
        $result = curl_exec($ch);
        
        if(empty($result)) {
           $arry['success'] = false;
           $arry['message'] = "No response from paypal";
        }
        else
        {
            $json = json_decode($result);
            $this->access_token = $json->access_token;
        }
        
        curl_close($ch);
        
        
    }
    
    
    public function processPayment($data) {
        
        
        $ch = curl_init();
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
        $data1 = '{
          "intent":"sale",
          "payer": {
            "payment_method": "credit_card",
            "funding_instruments": [
              {
                "credit_card": {
                  "number": "'.$data['cardnumber'].'",
                  "type": "'.$data['type'].'",
                  "expire_month": "'.$data['month'].'",
                  "expire_year": "'.$data['year'].'",
                  "cvv2": "'.$data['cvv'].'",
                  "first_name": "'.$firstName.'",
                  "last_name": "'.$lastName.'"
                }
              }
            ]
          },
          "transactions":[
            {
              "amount":{
                "total":"'.$data['price'].'",
                "currency":"'.strtoupper($data['currency']).'"
              },
              "description":"This is the payment transaction description."
            }
          ]
        }
        ';
        $uri = URI_SANDBOX . "payments/payment";
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$this->access_token));
        curl_setopt ($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
               
        $result = curl_exec($ch);
        
        if(empty($result)) {
         $arry['success'] = false;
         $arry['message'] = "No response from paypal";
        }
        else
        {
            $jsonData = rtrim($result, "\0");
            $json = json_decode($jsonData,TRUE);
        }
        
        $arry = array();
        if(isset($json['state'])) {
           $arry['success'] = true;
           $arry['message'] = "paypal payment saved";
           $data['id'] = $json['id'];
           $data['method'] = 'paypal';
           db::save_order($data);
        }
        else if(isset($json['name'])) {
           if($json['name']=='INTERNAL_SERVICE_ERROR') {
              $arry['message'] = "Use valid test credit card number for paypal";
           }
           else {
              $arry['message'] = $json['message'];
           }
           $arry['success'] = false; 
        }
        
        
        return $arry;
        
    } 
    
}
?>