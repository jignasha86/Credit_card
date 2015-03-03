<?php
  // Paypal 
  define("CLIENT_ID", "AXD8j_p11-YFoJqgj78Gw46LMwK3pXTMc9zaA7aMufXp-oEOyaS7Gz5OHYDbqT99J9xKQcfjpdQsNePl");
  define("CLIENT_SECRET", "EA3hcI0YtwEz41wtF4IoNnoMNXjh4T5TcIn67iSdi2QdNJ-uuA9PmEJ5_Qa5Ogh9tsD2Yeiej01FYHzc");
  define("URI_SANDBOX", "https://api.sandbox.paypal.com/v1/");
  define("URI_LIVE", "https://api.paypal.com/v1/");
  
  // Brain tree
  define("MERCHANT_ID", "4pgwh245hj2t94bt");
  define("PUBLIC_KEY", "q3sw4jww2jxgwj2h");
  define("PRIVATE_KEY", "a894b9bb3f3e1ca634f9da494196fb06");
  define("CSE_KEY", "MIIBCgKCAQEA4i6N+FG7pD9CSnafaDszBcNYKdbp+/EMZrWz/nFcVRiu4N+1WBzFN0HpNX0WBpOEhzq/2JyTxzm8aJIss3vFU1MqkwTv75tSgkDnw16RbJCVvv/A2fHxGz0E3tfKYGNkhPowk46jQccWMEbbF6lnXrP6+THTO/VWkq4dfyhOvTKa1Ik7kBWegJLt0tjQCBhpZ8YzK/vkvelGLCCgqt1nb1nEdV8hq3GwDdRf323SUC91XFqskdYHo");
  
  // Database
  
  define("HOST", "localhost");
  define("USERNAME", "root");
  define("PASSWORD", "");
  define("DATABASE","cards_1789067");
  
  mysql_connect(HOST, USERNAME,PASSWORD);
  
  if (!mysql_select_db(DATABASE)) {
      mysql_query('CREATE DATABASE '.DATABASE);
      mysql_select_db(DATABASE);
      $sql = "CREATE TABLE IF NOT EXISTS `orders` (
              `id` int(100) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `payment_id` varchar(100) NOT NULL,
              `method` varchar(50) NOT NULL,
              `name` varchar(100) NOT NULL,
              `price` int(100) NOT NULL,
              `currency` varchar(10) NOT NULL,
              `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
              `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
             ";
      mysql_query($sql);       
  }
  
  require_once BASE_PATH.'\classes\\db.class.php';                  
   
?>