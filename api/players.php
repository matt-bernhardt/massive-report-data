<?php
	include "../includes/block_conn_open.php";

	if(isset($_GET['term'])) {
		$term = '%' . $_GET['term'] . '%';
	} else {
		$term = '%';
	}

	$sql = "SELECT ID AS value, CONCAT(FirstName,' ',LastName) AS label, Position, Citizenship, DOB "
	."FROM tbl_players "
	."WHERE LastName LIKE '" . $term ."' "
	."ORDER BY LastName ASC, FirstName ASC";

	$teammates = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$rs = array();

	while($row = mysqli_fetch_assoc($teammates)) {
		$rs[] = $row;
	}

	$data = json_encode($rs);

	if(array_key_exists('callback',$_GET)) {

		header('Content-Type: text/javascript; charset=utf8');
	    header('Access-Control-Allow-Origin: http://www.example.com/');
	    header('Access-Control-Max-Age: 3628800');
	    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

	    $callback = $_GET['callback'];
	    echo $callback.'('.$data.');';		

	} else {
		header('Content-Type: application/json; charset=utf8');

		echo $data;
	}

	include "../includes/block_conn_close.php";
?>
