<?php
	
	require_once '../includes/DbOperations.php';

    $response = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		if (isset($_POST['Id'])) {
			$db = new DbOperations();

			$judge = $db->selectCompetitorInfoByID($_POST['Id']);

			$response['fotoURL'] = $judge['fotoURL'];
		} else
			$response['error'] = true;
		echo json_encode($response);
    }