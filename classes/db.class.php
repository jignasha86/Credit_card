<?php
class db {

public static function save_order($data) {

  $sql = 'INSERT INTO orders '.
       '(payment_id,method,name,price,currency,created) '.
       'VALUES ("'.$data['id'].'","'.$data['method'].'","'.$data['name'].'","'.$data['price'].'","'.$data['currency'].'", NOW() )';
   
  mysql_query($sql);     

}

}
?>