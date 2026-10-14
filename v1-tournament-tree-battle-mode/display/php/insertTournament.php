<?php

    require_once '../includes/DbOperations.php';

	$response = array();

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        
        if (isset($_POST['txtTournament'])) {
                $db = new DbOperations();
                
                if ($db->insertTournament($_POST['txtTournament'])){
                        $response['error'] = false;
                        $response['message'] = "Success!";
                        header("location: ../BackOffice.php?tournament");
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