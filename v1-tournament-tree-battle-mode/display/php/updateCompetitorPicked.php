<?php

	require_once '../includes/DbOperations.php';

	$response = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') {
	
		if (isset($_POST['ID']) and isset($_POST['Tid']) ) {
				$db = new DbOperations();
				
				if ($db->updateCompetitorPicked($_POST['Tid'], $_POST['ID'])) {
						$response['error'] = false;
						$response['message'] = "Success!";
					} else {
						$response['error'] = true;
						$response['message'] = "An error occurred, try again";
					}
		} else {
			$response['error'] = true;
			$response['message'] = "Required fields are missing";
		}
	} else {
		$response['error'] = true;
		$response['message'] = "Invalid Request";
	}

	echo json_encode($response);