<!DOCTYPE html>
<html lang="pt">
    <head>
		<title>Battle App - Server</title>
		<!-- Metadata -->
		<meta charset="utf-8">
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Battle App - Server" />
		<meta property="og:description" content="Página para gerir as batalhas da Battle App." />
		<meta name="theme-color" content="#ffffff" />
		<meta name="keywords" content="HTML, CSS, JS, PHP, BootStrap, Ajax, jQuery" />
		<meta name="author" content="Joel Monteiro" />
		<meta name="description" content="Página para gerir as batalhas da Battle App." />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<!-- Favicons -->
		<link rel="icon" type="image/svg+xml" href="../assets/icon/favicon.svg">
		<meta name="theme-color" content="#ffffff">

		<!-- Styles & Scripts -->
		<link rel="stylesheet" href="../css/bootstrap.min.css" />
		<link rel="stylesheet" href="../css/w3schools4.min.css" />
		<link rel="stylesheet" href="../css/server.css" />
		<script src="../js/jquery-3.3.1.min.js"></script>
		<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
		<script src="../js/firebase-config.js"></script>
	</head>
    
	<body style="background-color:#d4e7ff;">
		<!-- Session Init -->
		<?php
		session_start();

		if (!isset($_SESSION['User'])) {
			echo '<div class="divMessage"><b class="errorMessage">Area reservada a membros!</b></div>';
		?>
			<!-- if (!Session) Login -->
			<div id="divPerformLogin" class="divLogin">
				<form method="post" action="../php/login.php">
					<table>
						<tr>
							<td><label class="labelLogin">Username: &nbsp; </label></td>
							<td><input class="inputLogin" type="text" name="txtUser" required autofocus /></td>
						</tr>
						<tr>
							<td><label class="labelLogin">Password: &nbsp; </label></td>
							<td><input class="inputLogin" type="password" name="txtPass" required /></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" id="btnLogin" class="buttonLogin" value="Login" /></td>
						</tr>
					</table>
				</form>
			</div>
			
			<!-- Login Messages -->
			<?php
			if(isset($_GET['error']))
				echo '<div class="divMessage"><i class="errorMessage">Unexpected Error!</i></div>';
			elseif(isset($_GET['erro1']))
				echo '<div class="divMessage"><i class="errorMessage">Username ou Password incorrectos!</i></div>';
			elseif(isset($_GET['erro2']))
				echo '<div class="divMessage"><i class="errorMessage">Username nao consta na BD!</i></div>';
			elseif(isset($_GET['erro3']))
				echo '<div class="divMessage"><i class="errorMessage">Campos em Branco!</i></div>';
			elseif(isset($_GET['success']))
				echo '<div class="divMessage"><i class="sucessMessage">Bem vindo! '.$_SESSION['User'].'</i></div>';
			?>
		<?php
		} else {
			echo '<div class="divMessage"><i class="sucessMessage">Bem vindo! <b>'.$_SESSION['User'].'</b></i></div>';
		?>
			<!-- if (Session) Server -->
			<!-- Judges -->
			<div class="row divJudges">
				<div class="col-md-2"></div>			
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-1"></div>
						<!-- Judge #1 -->
						<div class="col-md-2">
							<div class="judgeFont">
								<label id="judge1"></label>
							</div>
							<button id="vote1" alt="Vote1" class="voteBorder"></button>
						</div>
						
						<!-- Judge #2 -->
						<div class="col-md-2">
							<div class="judgeFont">
								<label id="judge2"></label>
							</div>
							<button id="vote2" alt="Vote2" class="voteBorder"></button>
						</div>
						
						<!-- Judge #3 -->
						<div class="col-md-2">
							<div class="judgeFont">
								<label id="judge3"></label>
							</div>
							<button id="vote3" alt="Vote3" class="voteBorder"></button>
						</div>
						
						<!-- Judge #4 -->
						<div class="col-md-2">
							<div class="judgeFont">
								<label id="judge4"></label>
							</div>
							<button id="vote4" alt="Vote4" class="voteBorder"></button>
						</div>
						
						<!-- Judge #5 -->
						<div class="col-md-2">
							<div class="judgeFont">
								<label id="judge5"></label>
							</div>
							<button id="vote5" alt="Vote5" class="voteBorder"></button>
						</div>
						<div class="col-md-1"></div>
					</div>
				</div>
				<!-- Logout -->
				<div class="col-md-2">
					<a href="../php/logout.php" class="btn btn-info btn-lg" style="right: 0%"> Logout</a>
				</div>
			</div>
			
			<!-- Controls -->
			<div class="row divControls">
				<!-- Blue Corner -->
				<div class="col-md-4 divCompetitors blueCorner">
					<!-- Name -->
					<div class="row divBlueCorner">
						<div class="col-md-12">
							<p><label class="label">Nome</label></p>
							<p><input id="blueCornerName" type="text" class="input" placeholder="Blue Corner Name"></p>
						</div>
					</div>
					
					<!-- Points -->
					<div class="row divBluePoints">
						<div class="col-md-12">
							<p><label class="label">Pontos</label></p>
							<p><input id="blueCornerPoints" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="input" placeholder="Blue Corner Points"></p>
						</div>
					</div>
					
					<!-- Image -->
					<div class="row divBlueImage">
						<div class="col-md-12">
							<p><label class="label">Imagem</label></p>
							<p><input id="blueCornerImage" type="text" class="input" placeholder="Blue Corner Image"></p>
						</div>
					</div>
				</div>
				
				<!-- Battle Info -->
				<div class="col-md-4 divBattleInfo">
					<!-- Tournament -->
					<div class="row divTournament">
						<div class="col-md-12">
							<p><label class="label">Categoria</label></p>
							<p><input id="tournamentName" type="text" class="input" placeholder="Categoria"></p>
						</div>
					</div>
					
					<!-- Point - Point -->
					<div class="row divPoints">
						<div class="col-md-12">
							<p><label class="label">Pontuação</label></p>
							<p><input id="pointsName" type="text" oninput="this.value = this.value.replace(/[^0-9-]/g, '').replace(/(\..*)\./g, '$1');" class="input" placeholder="Pontuação" disabled></p>
						</div>
					</div>
					
					<!-- Round # -->
					<div class="row divPoints">
						<div class="col-md-12">
							<p><label class="label">Round</label></p>
							<p><input id="roundName" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="input" placeholder="Round" disabled></p>
						</div>
					</div>
					
					<!-- Next Round -->
					<div class="row divNextRound">
						<div class="col-md-12">
							<button id="buttonNextRound" class="button">Next Round</button>
						</div>
					</div>
					
					<!-- Next Battle -->
					<div class="row divNextBattle">
						<div class="col-md-12">
							<button id="buttonNextBattle" class="button">Next Battle</button>
						</div>
					</div>

					<!-- Reset -->
					<div class="row divReset">
						<div class="col-md-12">
							<button id="buttonReset" class="button">Reset</button>
						</div>
					</div>
					
					<!-- Fechar -->
					<div class="row divClose">
						<div class="col-md-12">
							<button id="buttonClose" class="button">Fechar</button>
						</div>
					</div>
					
					<!-- Constant NumJudges / NumRounds -->
					<div class="row divConstants">
						<div class="col-md-6">
							<p><label class="label">Número Judges</label></p>
							<p>
								<label for="lbl3">3</label>
								<input type="radio" name="numJudges" value="3" id="lbl3" class="radio" style="margin-right: 25px;" disabled>
								<label for="lbl5">5</label>
								<input type="radio" name="numJudges" value="5" id="lbl5" class="radio" disabled>
							</p>
						</div>
						<div class="col-md-6">
							<p><label class="label">Número Rounds</label></p>
							<p><input id="numRounds" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="inputNumber" placeholder="Número Rounds"></p>
						</div>
					</div>
				</div>
				
				<!-- Red Corner -->
				<div class="col-md-4 divCompetitors redCorner">
					<!-- Name -->
					<div class="row divRedCorner">
						<div class="col-md-12">
							<p><label class="label">Nome</label></p>
							<p><input id="redCornerName" type="text" class="input" placeholder="Red Corner Name"></p>
						</div>
					</div>
					
					<!-- Points -->
					<div class="row divRedPoints">
						<div class="col-md-12">
							<p><label class="label">Pontos</label></p>
							<p><input id="redCornerPoints" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="input" placeholder="Red Corner Points"></p>
						</div>
					</div>
					
					<!-- Image -->
					<div class="row divRedImage">
						<div class="col-md-12">
							<p><label class="label">Imagem</label></p>
							<p><input id="redCornerImage" type="text" class="input" placeholder="Red Corner Image"></p>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		?>
		<script src="../js/server.js"></script>
    </body>
</html>