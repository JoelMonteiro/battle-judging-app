<?php

    require_once '../includes/DbOperations.php';

    if ($_SERVER['REQUEST_METHOD']=='POST') {

        $db = new DbOperations();

        $tournaments = $db->selectTournamentsNotPicked();

        echo json_encode($tournaments);
    }