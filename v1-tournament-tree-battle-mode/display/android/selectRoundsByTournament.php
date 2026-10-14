<?php

    require_once '../includes/DbOperations.php';

    if ($_SERVER['REQUEST_METHOD']=='POST') {
		if (isset($_POST['Id'])) {
			$db = new DbOperations();

			$battles = $db->selectRoundsByTournament($_POST['Id']);
			$battles['error'] = false;
		} else
			$battles['error'] = true;
		echo json_encode($battles);
    }
