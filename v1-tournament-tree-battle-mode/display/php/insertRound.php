<?php

	require_once '../includes/DbOperations.php';

	$response = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') {
	
		if (
			isset($_POST['blueCorner']) and 
				isset($_POST['redCorner']) and 
					isset($_POST['tournamentID']) and
						isset($_POST['order']) and
							isset($_POST['blueCornerID']) and
								isset($_POST['redCornerID'])
			) {
				$db = new DbOperations();
				
				if ($db->insertRound(
					$_POST['tournamentID'],
					$_POST['blueCorner'],
					$_POST['redCorner'],
					$_POST['order'],
					$_POST['blueCornerID'],
					$_POST['redCornerID']
					)){
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