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
		
		<!-- Cache -->
		<link rel="preload" href="assets/competitors/ze.PNG" as="image">
		<link rel="preload" href="assets/competitors/lorenzo.PNG" as="image">
		<link rel="preload" href="assets/competitors/yann.PNG" as="image">
		<link rel="preload" href="assets/competitors/flavio.PNG" as="image">
		<link rel="preload" href="assets/competitors/jess.PNG" as="image">
		<link rel="preload" href="assets/competitors/enes.PNG" as="image">
		<link rel="preload" href="assets/competitors/bia.PNG" as="image">
		<link rel="preload" href="assets/competitors/oscar.PNG" as="image">
		<link rel="preload" href="assets/competitors/angel.PNG" as="image">
		<link rel="preload" href="assets/competitors/glima.PNG" as="image">
		<link rel="preload" href="assets/competitors/joao.PNG" as="image">
		<link rel="preload" href="assets/competitors/baba.PNG" as="image">
		<link rel="preload" href="assets/competitors/sebas.PNG" as="image">
		<link rel="preload" href="assets/competitors/leo.PNG" as="image">
		<link rel="preload" href="assets/competitors/valentim.PNG" as="image">
		
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
			<div class="winnerLabelDiv">
				<label id="winnerLabelText" class="winnerLabelText"></label>
			</div>
			<div class="divWinnerContainer">
				<div class="winnerBattle">
					<div class="divLabel">
						<label id="winnerBattle"></label>
					</div>
					<div class="divImage">
						<img src="" id="imgWinner" alt="" class="w3-hover-opacity">
					</div>
				</div>
			</div>
		</div>

		<!-- Judges -->
		<div class="row divJudges">
            <div class="col-md-2">
				<img src="assets/image/logo.png" id="logo" alt="Battle App" class="w3-hover-opacity logo">
			</div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" style="z-index: 1;">
                        <div class="judgeFont">
                            <label id="judge1"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote1" alt="Vote1" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2" style="z-index: 1;">
                        <div class="judgeFont">
                            <label id="judge2"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote2" alt="Vote2" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2" style="z-index: 1;">
                        <div class="judgeFont">
                            <label id="judge3"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote3" alt="Vote3" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2" style="z-index: 1;">
                        <div class="judgeFont">
                            <label id="judge4"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote4" alt="Vote4" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-2" style="z-index: 1;">
                        <div class="judgeFont">
                            <label id="judge5"></label>
                        </div>
                        <img src="assets/image/default.png" id="vote5" alt="Vote5" class="w3-hover-opacity voteBorder">
                    </div>
                    <div class="col-md-1"></div>
                </div>
				<div class="backgroundBorder"> </div>
            </div>
            <div class="col-md-2">
			</div>
        </div>

		<!-- Round -->
        <div class="row divRoundCompetitorInfo">
			<!-- Blue Corner -->
            <div class="col-md-4 divCompetitors">
                <img src="" id="imgBlueCorner" alt="" class="w3-hover-opacity competitorBorderLeft">
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
                <div class="pointsBorder">
                    <div class="pointsFont">
                        <label id="pointNumbers"></label>
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
				<div class="logoBorder">
					<img src="assets/image/vs.png" id="vs" alt="vs" class="w3-hover-opacity vs">
				</div>
            </div>
			
			<!-- Red Corner -->
            <div class="col-md-4 divCompetitors">
                <img src="" id="imgRedCorner" alt="" class="w3-hover-opacity competitorBorderRight">
                <div class="competitorName">
                    <p class="competitorFontRight"><label id="redCorner"></label></p>
                </div>
            </div>
        </div>
		
		<div class="hidden">
			<script type="text/javascript">
				<!--//--><![CDATA[//><!--
				function preloadImages(array, waitForOtherResources, timeout) {
					var loaded = false, list = preloadImages.list, imgs = array.slice(0), t = timeout || 15*1000, timer;
					if (!preloadImages.list) {
						preloadImages.list = [];
					}
					if (!waitForOtherResources || document.readyState === 'complete') {
						loadNow();
					} else {
						window.addEventListener("load", function() {
							clearTimeout(timer);
							loadNow();
						});
						// in case window.addEventListener doesn't get called (sometimes some resource gets stuck)
						// then preload the images anyway after some timeout time
						timer = setTimeout(loadNow, t);
					}

					function loadNow() {
						if (!loaded) {
							loaded = true;
							for (var i = 0; i < imgs.length; i++) {
								var img = new Image();
								img.onload = img.onerror = img.onabort = function() {
									var index = list.indexOf(this);
									if (index !== -1) {
										// remove image from the array once it's loaded
										// for memory consumption reasons
										list.splice(index, 1);
									}
								}
								list.push(img);
								img.src = imgs[i];
							}
						}
					}
				}

				preloadImages(["assets/competitors/ze.PNG", "assets/competitors/lorenzo.PNG",  "assets/competitors/yann.PNG", "assets/competitors/flavio.PNG", "assets/competitors/jess.PNG", "assets/competitors/enes.PNG",
				 "assets/competitors/bia.PNG", "assets/competitors/oscar.PNG", "assets/competitors/angel.PNG", "assets/competitors/glima.PNG", "assets/competitors/joao.PNG", "assets/competitors/sebas.PNG",
				  "assets/competitors/baba.PNG", "assets/competitors/leo.PNG", "assets/competitors/valentim.PNG"], true);
				//--><!]]>
			</script>
		</div>
    </body>
</html>
