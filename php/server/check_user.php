<?php
	require('./conector.php');
	$con = new ConectorBD('localhost', 'user_agenda', '123456');
	echo $con->checkUser();
?>