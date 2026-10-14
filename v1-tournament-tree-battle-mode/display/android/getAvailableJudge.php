<?php

    require_once '../includes/DbOperations.php';

    $response = array();
    $response["judges"] = array();

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $db = new DbOperations();

        $judge = $db->getAvailableJudge();
        if (!$judge) { // não há lugares livres: devolve a lista vazia
            echo json_encode($response);
            exit;
        }

        $item = array();
        $item['ID'] = $judge['ID'];
        $item['nome'] = $judge['nome'];
        array_push($response["judges"], $item);

        echo json_encode($response);
    }