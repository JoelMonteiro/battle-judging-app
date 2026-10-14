<?php

	function utf8ize($d) {
		if (is_array($d)) {
			foreach ($d as $k => $v) {
				$d[$k] = utf8ize($v);
			}
		} else if (is_string ($d)) {
			return utf8_encode($d);
		}
		return $d;
	}

	require_once '../includes/DbOperations.php';
	
	$db = new DbOperations();

	$tournamentID = $_GET['tournamentID'];
	$result = $db->selectAllCompetitorsByTournament($tournamentID);

	foreach ($result as &$value)
		$competitors = $value;

	echo json_encode(($competitors));
