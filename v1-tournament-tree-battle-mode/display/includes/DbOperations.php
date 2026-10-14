<?php

    class DbOperations {

        private $con;

        function __construct() {
            require_once dirname(__FILE__).'/DbConnect.php';
            
            $db = new DbConnect();

            $this->con = $db->connect();
        }


        /* - Judges - */
        function getAvailableJudge() {
            $sttm = $this->con->prepare("SELECT * FROM judges WHERE connected = false LIMIT 1");
            $sttm->execute();
            return $sttm->get_result()->fetch_assoc();
        }


        function updateJudgeById($id, $connected) {
            $sttm = $this->con->prepare("UPDATE judges SET connected = ? WHERE ID = ?");
            $sttm->bind_param("dd", $connected, $id);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
        }


		

        /* - Competitors - */
        function insertCompetitor($nome, $genero) {
            $sttm = $this->con->prepare("INSERT INTO `competitors`(`nome`, `genero`, `picked`) VALUES(?, ?, 0)");
            $sttm->bind_param("ss", $nome, $genero);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
        }

		function selectAllCompetitors() {
			$sttm = $this->con->prepare("SELECT fldId, nome FROM competitors WHERE picked = 0");
			$sttm->execute();
			$sttm->store_result();
			$sttm->bind_result($fldID, $nome);
			
			$response = array();
			$response['competitors'] = array();

			if ($sttm->num_rows > 0) {
				while ($row = $sttm->fetch()) {
					$item = array();
					$item['fldID'] = $fldID;
					$item['nome'] = $nome;
					array_push($response['competitors'], $item);
				}
			}
			
			return $response;
		}

        function selectCompetitorInfoByID($id) {
            $sttm = $this->con->prepare("SELECT fotoURL FROM competitors WHERE fldID = ?");
            $sttm->bind_param("d", $id);
            $sttm->execute();
            return $sttm->get_result()->fetch_assoc();
        }

		function updateCompetitorPicked($Tid, $ID) {
			$sttm = $this->con->prepare("UPDATE competitors SET picked = 1, IdTorneio = ? WHERE fldID = ?");
            $sttm->bind_param("dd", $Tid, $ID);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
		}


        /* - Torneio - */
        function insertTournament($nome) {
            $sttm = $this->con->prepare("INSERT INTO tournaments(nome, picked, ongoing, ready) VALUES(?, 0, 0, 0)");
            $sttm->bind_param("s", $nome);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
        }

		function selectAllTournaments() {
			$sttm = $this->con->prepare("SELECT `fldId`, `nome` FROM `tournaments` WHERE ready = 0");
			$sttm->execute();
			return $sttm->get_result();
		}
		
		function selectAllActiveTournaments() {
			$sttm = $this->con->prepare("SELECT `fldId`, `nome` FROM `tournaments` WHERE ready = 1");
			$sttm->execute();
			return $sttm->get_result();
		}

        function selectTournamentsNotPicked() {
            $sttm = $this->con->prepare("SELECT * FROM tournaments WHERE picked = 0 AND ready = 1");
            $sttm->execute();
			$sttm->store_result();
			$sttm->bind_result($fldID, $nome, $picked, $ongoing, $ready);
			
			$response = array();
			$response['tournaments'] = array();

			if ($sttm->num_rows > 0) {
				while ($row = $sttm->fetch()) {
					$item = array();
					$item['fldID'] = $fldID;
					$item['nome'] = $nome;
					array_push($response['tournaments'], $item);
				}
			}
			
			return $response;
        }

		function updateTournamentReady($tournamentID) {
			$sttm = $this->con->prepare("UPDATE tournaments SET ready = 1 WHERE fldID = ?");
            $sttm->bind_param("d", $tournamentID);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
		}

        function updateTournament($tournamentID) {
            $sttm = $this->con->prepare("UPDATE tournaments SET picked = 1 WHERE fldID = ?");
            $sttm->bind_param("d", $tournamentID);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
        }


		/* - Competitor-Tournament - */
		function selectAllCompetitorsByTournament($tournamentID) {
			$sttm = $this->con->prepare("SELECT trk.fldID, trk.nome, t.nome as 'Tnome' FROM competitors trk, tournaments t WHERE trk.IdTorneio = t.fldID AND t.fldID = ?");
			$sttm->bind_param("d", $tournamentID);
            $sttm->execute();
			$sttm->store_result();
			$sttm->bind_result($fldID, $nome, $Tnome);
			
			$response = array();
			$response['competitors'] = array();

			if ($sttm->num_rows > 0) {
				while ($row = $sttm->fetch()) {
					$item = array();
					$item['fldID'] = $fldID;
					$item['nome'] = $nome;
					array_push($response['competitors'], $item);
				}
			}
			
			return $response;
		}


        /* - Rounds - */
        function insertRound($tournamentID, $blueCorner, $redCorner, $order, $blueCornerID, $redCornerID) {
            $sttm = $this->con->prepare("INSERT INTO round(tournamentID, blueCorner, redCorner, ordem, blueCornerID, redCornerID) VALUES(?, ?, ?, ?, ?, ?)");
            $sttm->bind_param("dssddd", $tournamentID, $blueCorner, $redCorner, $order, $blueCornerID, $redCornerID);
            if ($sttm->execute()) {
                return true;
            } else {
                return false;
            }
        }

		function selectRoundsByTournament($tournamentID) {
			$sttm = $this->con->prepare(
				"SELECT r.fldID, r.blueCorner, r.blueCornerID, r.redCorner, r.redCornerID, r.tournamentID, r.ordem,
				        b.fotoURL AS blueCornerImage, rd.fotoURL AS redCornerImage
				 FROM round r
				 LEFT JOIN competitors b  ON b.fldID  = r.blueCornerID
				 LEFT JOIN competitors rd ON rd.fldID = r.redCornerID
				 WHERE r.tournamentID = ? AND (r.blueCornerID <> 0 OR r.redCornerID <> 0)
				 ORDER BY r.ordem");
			// LEFT JOIN: com o JOIN normal, as batalhas com um lado vazio ("---", ID 0) desapareciam
			// porque não existe competidor com fldID 0.
			$sttm->bind_param("d", $tournamentID);
            $sttm->execute();
			$sttm->store_result();
			$sttm->bind_result($fldID, $blueCorner, $blueCornerID, $redCorner, $redCornerID, $tournamentID, $ordem, $blueCornerImage, $redCornerImage);
			
			$response = array();
			$response['battles'] = array();

			if ($sttm->num_rows > 0) {
				while ($row = $sttm->fetch()) {
					$item = array();
					$item['fldID'] = $fldID;
					$item['blueCornerName'] = $blueCorner;
					$item['blueCornerID'] = $blueCornerID;
					$item['redCornerName'] = $redCorner;
					$item['redCornerID'] = $redCornerID;
					$item['blueCornerImage'] = $blueCornerImage;
					$item['redCornerImage'] = $redCornerImage;
					$item['tournamentID'] = $tournamentID;
					$item['ordem'] = $ordem;
					array_push($response['battles'], $item);
				}
			}
			
			return $response;
		}
		
    }