<!DOCTYPE html>
<html lang="pt">
    <head>
		<title>Battle App - Judge</title>
		<!-- Metadata -->
		<meta charset="utf-8">
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Battle App - Judge" />
		<meta property="og:description" content="Página de Judge para votar na Battle App." />
		<meta name="theme-color" content="#ffffff" />
		<meta name="keywords" content="HTML, CSS, JS, PHP, BootStrap, Ajax, jQuery" />
		<meta name="author" content="Joel Monteiro" />
		<meta name="description" content="Página de Judge para votar na Battle App." />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<!-- Favicons -->
		<link rel="icon" type="image/svg+xml" href="../assets/icon/favicon.svg">
		<meta name="theme-color" content="#ffffff">

		<!-- Styles & Scripts -->
		<link rel="stylesheet" href="../css/bootstrap.min.css" />
		<link rel="stylesheet" href="../css/w3schools4.min.css" />
		<link rel="stylesheet" href="../css/judge.css" />
		<script src="../js/jquery-3.3.1.min.js"></script>
		<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
		<script src="../js/firebase-config.js"></script>
	</head>

    <body style="background-color:#d4e7ff;">
		<!-- Dialog -->
		<div class="dialogContainer">
			<div class="dialogContainerImage"></div>
		</div>
		
		<!-- Information -->
		<div class="row divInformation">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<!-- Judge Number -->
				<div class="divJudgeNumber">
					<label id="judgeNumber" class="labelJudgeNumber">2</label>
				</div>
				
				<!-- Judge Name -->
				<div class="divJudgeName">
					<input id="judgeName" type="text" class="inputJudgeName" placeholder="Nome">
					<button id="buttonSendJudgeName" class="buttonSendJudgeName">Enviar</button>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
		
		<!-- Buttons -->
		<div class="row divButtons">
			<!-- BLUE -->
			<div class="col-md-4">
				<div class="col-md-12 divVote">
					<button id="buttonVoteBlue" class="blueCorner"></button>
				</div>
			</div>
			
			<!-- DRAW -->
			<div class="col-md-4">
				<div class="col-md-12 divVote">
					<button id="buttonVoteDraw" class="drawCorner"></button>
				</div>
			</div>
			
			<!-- RED -->
			<div class="col-md-4">
				<div class="col-md-12 divVote">
					<button id="buttonVoteRed" class="redCorner"></button>
				</div>
			</div>
		</div>
		
		<script src="../js/judge2.js"></script>
    </body>
</html>