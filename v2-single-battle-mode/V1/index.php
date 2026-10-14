<!DOCTYPE html>
<html lang="pt">
    <head>
		<title>Battle App</title>
		<!-- Metadata -->
		<meta charset="utf-8">
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Battle App" />
		<meta property="og:description" content="Página da Battle App." />
		<meta name="theme-color" content="#ffffff" />
		<meta name="keywords" content="HTML, CSS, JS, PHP, BootStrap, Ajax, jQuery" />
		<meta name="author" content="Joel Monteiro" />
		<meta name="description" content="Página da Battle App" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<!-- Favicons -->
		<link rel="icon" type="image/svg+xml" href="assets/icon/favicon.svg">
		<meta name="theme-color" content="#ffffff">

		<!-- Styles & Scripts -->
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/w3schools4.min.css" />
		<link rel="stylesheet" href="css/display.css" />
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
		<script src="../js/firebase-config.js"></script>
		<script src="js/display.js"></script>
	</head>

    <body style="background-color:#e5e5e5;">
		<!-- Dialog Container -->
		<div class="dialogContainer">
			<div class="dialogContainerImage"></div>
		</div>
		
		<!-- Winner Container -->
		<div class="winnerContainer">
			<div class="divWinnerContainer">
				<p class="winnerBattle"><label id="winnerBattle"></label></p>
			</div>
		</div>
	
		<!-- Judges -->
		<div class="row divJudges">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge1"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote1" alt="Vote1" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge2"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote2" alt="Vote2" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge3"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote3" alt="Vote3" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge4"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote4" alt="Vote4" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge5"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote5" alt="Vote5" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

		<!-- Round -->
        <div class="row divRoundCompetitorInfo">
			<!-- Blue Corner -->
            <div class="col-md-4 divCompetitors">
				<div class="divPointsLeft">
					<p class="pointsFont"><label id="bluePoints"></label></p>
				</div>
                <div class="competitorName">
                    <p class="competitorFontLeft"><label id="blueCorner"></label></p>
                </div>
            </div>
			
			<!-- Round Info -->
            <div class="col-md-4 divRoundPoints">
                <div class="pointsBorder">
                    <div class="tournamentFont">
						<label id="tournament"></label>
                    </div>
                </div>    
                <div class="roundBorder">
                    <div class="divRound">
                        <p class="roundFont"><label id="roundNumber"></label></p>
                    </div>
                </div>
				<div class="winnerBorder">
                    <div class="divWinner">
                        <p class="winnerFont"><label id="winnerRound"></label></p>
                    </div>
                </div>
            </div>
			
			<!-- Red Corner -->
            <div class="col-md-4 divCompetitors">
				<div class="divPointsRight">
					<p class="pointsFont"><label id="redPoints"></label></p>
				</div>
                <div class="competitorName">
                    <p class="competitorFontRight"><label id="redCorner"></label></p>
                </div>
            </div>
        </div>
    </body>
</html>
