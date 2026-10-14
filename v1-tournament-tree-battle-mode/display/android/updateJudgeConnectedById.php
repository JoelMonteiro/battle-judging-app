<?php

	require_once '../includes/DbOperations.php';

	$response = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') {
	
		if (
			isset($_POST['Id']) and 
				isset($_POST['connected'])
			) {
				$db = new DbOperations();
				
				if ($db->updateJudgeById(
					$_POST['Id'],
					$_POST['connected']
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