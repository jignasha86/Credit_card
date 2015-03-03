<?php
set_time_limit(0);
header('Content-Type: application/json');

spl_autoload_register(function ($class) {
    require_once 'classes/payment/' . $class . '.php';
});

if(isset($_POST)) {       
	require_once "classes/validation.class.php";
	$cvalidate = new CreditcardValidator($_POST);
	echo json_encode($cvalidate->getResponse());
}

?>