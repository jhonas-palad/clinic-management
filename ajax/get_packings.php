<?php 
	include '../config/connection.php';

  	$medicineId = $_GET['medicine_id'];

  	$query = "SELECT `id`,`total_capsules` from `medicine_details` 
  	where `id` = $medicineId;";

  	try {
  		$stmt = $con->prepare($query);
  		$stmt->execute();

  		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$total_capsules = intval($row['total_capsules']);
  		}

  	} catch(PDOException $ex) {
  		echo $ex->getTraceAsString();
  		exit;
  	}

  	echo $total_capsules;
?>