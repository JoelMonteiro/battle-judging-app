<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Battle App - Display</title>
		<link rel="icon" type="image/svg+xml" href="assets/icon/favicon.svg">


        <!--Metadata-->
        <meta charset="utf-8">
        <meta name="theme-color" content="#ffffff">
        <meta name="author" content="Joel Monteiro" />
        <meta name="revised" content="Joel Monteiro, 27/05/2018" />
        <meta name="keywords" content="HTML, CSS, JS, PHP, BootStrap, Ajax, jQuery" />
        <meta name="description" content="Página de Display do Battle App." />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!--Icons-->

		<!--Styles-->
		<link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/w3schools4.min.css" />
		<link rel="stylesheet" href="css/display.css" />

		<!--Scripts-->
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
		<script src="js/firebase-config.js"></script>
		<script src="js/display.js"></script>
	</head>
    <body style="background-color:#e5e5e5;">

		<div class="row divJudges">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge1"></label>
                        </div>
                        <img src="assets/img/default.png" id="vote1" alt="Vote1" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge2"></label>
                        </div>
                        <img src="assets/img/default.png" id="vote2" alt="Vote2" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge3"></label>
                        </div>
                        <img src="assets/img/default.png" id="vote3" alt="Vote3" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge4"></label>
                        </div>
                        <img src="assets/img/default.png" id="vote4" alt="Vote4" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2">
                        <div class="judgeFont">
                            <label id="judge5"></label>
                        </div>
                        <img src="assets/img/default.png" id="vote5" alt="Vote5" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

        <div class="row divRoundCompetitorInfo">
            <div class="col-md-4 divCompetitors">
				<div class="divPointsLeft">
					<p class="pointsFont"><label id="bluePoints"></label></p>
				</div>
                <div class="competitorName">
                    <p class="competitorFontLeft"><label id="blueCorner"></label></p>
                </div>
            </div>
            <div class="col-md-4 divRoundPoints">
                <div class="pointsBorder">
                    <div class="tournamentFont">
						<label id="tournament"></label>
                        <!--<label id="pointNumbers"></label>-->
                    </div>
                </div>    
                <div class="roundBorder">
                    <div class="divRound">
                        <p class="roundFont"><label id="roundNumber"></label></p>
                    </div>
                </div>
				<div class="winnerBorder">
                    <div class="divWinner">
                        <p class="winnerFont"><label id="winner"></label></p>
                    </div>
                </div>
            </div>
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
