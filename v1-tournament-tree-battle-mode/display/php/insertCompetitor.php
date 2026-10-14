<?php

    require_once '../includes/DbOperations.php';

	$response = array();

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        
        if (isset($_POST['txtName'])) {
                $db = new DbOperations();
                
				$gender = (isset($_POST['txtGender']) ? $_POST['txtGender']: NULL);

                if ($db->insertCompetitor($_POST['txtName'], $_POST['txtGender'])) {
                        $response['error'] = false;
                        $response['message'] = "Success!";
                        header("location: ../BackOffice.php?competitor");
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