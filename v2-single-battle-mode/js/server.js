/*	-----	-----	-----	-----	FIREBASE LOAD	-----	-----	-----	-----	*/
$(window).on("load", function () {
	var database = firebase.database();
    var rootRef = database.ref().child('RoundVoteInfo');
	
	rootRef.on('value', function(snapshot){
		/*	-----	-----	-----	-----	Judges		-----	-----	-----	-----	*/
		$('#judge1').text(snapshot.child('Judge1').val());
		$('#judge2').text(snapshot.child('Judge2').val());
		$('#judge3').text(snapshot.child('Judge3').val());
		$('#judge4').text(snapshot.child('Judge4').val());
		$('#judge5').text(snapshot.child('Judge5').val());

		var vote1 = snapshot.child('Vote1').val();
		switch (vote1) {
			case "BLUE": $('#vote1').css({"background-image": "url(assets/image/blue.png)"}); break;
			case "DRAW": $('#vote1').css({"background-image": "url(assets/image/draw.png)"}); break;
			case "RED": $('#vote1').css({"background-image": "url(assets/image/red.png)"}); break;
			case "BLANK": $('#vote1').css({"background-image": "url(assets/image/default.png)"}); break;
		}
		var vote2 = snapshot.child('Vote2').val();
		switch (vote2) {
			case "BLUE": $('#vote2').css({"background-image": "url(assets/image/blue.png)"}); break;
			case "DRAW": $('#vote2').css({"background-image": "url(assets/image/draw.png)"}); break;
			case "RED": $('#vote2').css({"background-image": "url(assets/image/red.png)"}); break;
			case "BLANK": $('#vote2').css({"background-image": "url(assets/image/default.png)"}); break;
		}
		var vote3 = snapshot.child('Vote3').val();
		switch (vote3) {
			case "BLUE": $('#vote3').css({"background-image": "url(assets/image/blue.png)"}); break;
			case "DRAW": $('#vote3').css({"background-image": "url(assets/image/draw.png)"}); break;
			case "RED": $('#vote3').css({"background-image": "url(assets/image/red.png)"}); break;
			case "BLANK": $('#vote3').css({"background-image": "url(assets/image/default.png)"}); break;
		}
		var vote4 = snapshot.child('Vote4').val();
		switch (vote4) {
			case "BLUE": $('#vote4').css({"background-image": "url(assets/image/blue.png)"}); break;
			case "DRAW": $('#vote4').css({"background-image": "url(assets/image/draw.png)"}); break;
			case "RED": $('#vote4').css({"background-image": "url(assets/image/red.png)"}); break;
			case "BLANK": $('#vote4').css({"background-image": "url(assets/image/default.png)"}); break;
		}
		var vote5 = snapshot.child('Vote5').val();
		switch (vote5) {
			case "BLUE": $('#vote5').css({"background-image": "url(assets/image/blue.png)"}); break;
			case "DRAW": $('#vote5').css({"background-image": "url(assets/image/draw.png)"}); break;
			case "RED": $('#vote5').css({"background-image": "url(assets/image/red.png)"}); break;
			case "BLANK": $('#vote5').css({"background-image": "url(assets/image/default.png)"}); break;
		}
		
		/*	-----	-----	-----	-----	Blue Corner	-----	-----	-----	-----	*/
		$('#blueCornerName').val(snapshot.child('BlueCorner').val());
		$('#blueCornerPoints').val(snapshot.child('BluePoints').val());
		$('#blueCornerImage').val(snapshot.child('BlueCornerImage').val());
		
		/*	-----	-----	-----	-----	Battle Info	-----	-----	-----	-----	*/
		$('#tournamentName').val(snapshot.child('Tournament').val());
		$('#pointsName').val(snapshot.child('Points').val());
		$('#roundName').val(snapshot.child('Round').val());
		
		$('#numRounds').val(snapshot.child('NRounds').val());
		var numJudges = snapshot.child('NJudges').val();
		if (numJudges != null) {
			if (numJudges === 3) {
				$("input[name=numJudges][value=3]").prop('checked', true);
				$("input[name=numJudges][value=5]").prop('checked', false);
			}
			else if (numJudges === 5) {
				$("input[name=numJudges][value=3]").prop('checked', false);
				$("input[name=numJudges][value=5]").prop('checked', true);
			} else {
				$("input[name=numJudges][value=3]").prop('checked', false);
				$("input[name=numJudges][value=5]").prop('checked', false);
			}
		}
		
		/*	-----	-----	-----	-----	Red Corner	-----	-----	-----	-----	*/
		$('#redCornerName').val(snapshot.child('RedCorner').val());
		$('#redCornerPoints').val(snapshot.child('RedPoints').val());
		$('#redCornerImage').val(snapshot.child('RedCornerImage').val());
		
		/*	-----	-----	-----	-----	Listener	-----	-----	-----	-----	*/
		var nReds = 0;
		var nBlues = 0;
		var nVotes = 0;
		var nVotes3 = 0;
		var nVotes5 = 0;
		var nJudges = parseInt(snapshot.child('NJudges').val());
		var nRounds = parseInt(snapshot.child('NRounds').val());
		var redPoints = parseInt(snapshot.child('RedPoints').val());
		var bluePoints = parseInt(snapshot.child('BluePoints').val());
		var redCorner = snapshot.child('RedCorner').val();
		var blueCorner = snapshot.child('BlueCorner').val();
		var redCornerImg = snapshot.child('RedCornerImage').val();
		var blueCornerImg = snapshot.child('BlueCornerImage').val();
		var winnerPic = "";
		var winnerRound = "";
		var winnerBattle = "";
		
		if (!snapshot.child('WinnerRound').val().trim()) {
			// Gather votes
			if (vote1 != null) {
				if (vote1 === "BLUE") {
					nBlues++;
				}
				if (vote1 === "RED") {
					nReds++;
				}
				if (vote1 !== "BLANK") {
					nVotes++;
					nVotes3++;
					nVotes5++;
				}
			}
			if (vote2 != null) {
				if (vote2 === "BLUE") {
					nBlues++;
				}
				if (vote2 === "RED") {
					nReds++;
				}
				if (vote2 !== "BLANK") {
					nVotes++;
					nVotes3++;
					nVotes5++;
				}
			}
			if (vote3 != null) {
				if (vote3 === "BLUE") {
					nBlues++;
				}
				if (vote3 === "RED") {
					nReds++;
				}
				if (vote3 !== "BLANK") {
					nVotes++;
					nVotes3++;
					nVotes5++;
				}
			}
			if (vote4 != null) {
				if (vote4 === "BLUE") {
					nBlues++;
				}
				if (vote4 === "RED") {
					nReds++;
				}
				if (vote4 !== "BLANK") {
					nVotes++;
					nVotes5++;
				}
			}
			if (vote5 != null) {
				if (vote5 === "BLUE") {
					nBlues++;
				}
				if (vote5 === "RED") {
					nReds++;
				}
				if (vote5 !== "BLANK") {
					nVotes++;
					nVotes5++;
				}
			}
			
			// Calculate Winner
			if (nJudges === 5 && nVotes5 === 5) {
				if (nBlues > nReds) {
					bluePoints++;
					winnerRound = "" + blueCorner;
					if (((nRounds/2) < bluePoints) || ((nRounds/2) < redPoints) || (bluePoints + redPoints === nRounds)) {
						if (bluePoints > redPoints) {
							winnerBattle = "" + blueCorner;
							winnerPic = "" + blueCornerImg;
						}
						else if (redPoints > bluePoints) {
							winnerBattle = "" + redCorner;
							winnerPic = "" + redCornerImg;
						}					
					}
					var info = {
						BluePoints: parseInt(bluePoints),
						RedPoints: parseInt(redPoints),
						Points: bluePoints + "-" + redPoints,
						WinnerBattle: winnerBattle,
						WinnerRound: winnerRound,
						WinnerPic: winnerPic
					}
					
					database.ref().child('RoundVoteInfo').update(info);
				} else if (nReds > nBlues) {
					redPoints++;
					winnerRound = "" + redCorner;
					if (((nRounds/2) < bluePoints) || ((nRounds/2) < redPoints) || (bluePoints + redPoints === nRounds)) {
						if (bluePoints > redPoints) {
							winnerBattle = "" + blueCorner;
							winnerPic = "" + blueCornerImg;
						}
						else if (redPoints > bluePoints) {
							winnerBattle = "" + redCorner;
							winnerPic = "" + redCornerImg;
						}
					}
					var info = {
						BluePoints: parseInt(bluePoints),
						RedPoints: parseInt(redPoints),
						Points: bluePoints + "-" + redPoints,
						WinnerBattle: winnerBattle,
						WinnerRound: winnerRound,
						WinnerPic: winnerPic
					}
					
					database.ref().child('RoundVoteInfo').update(info);
				} else {
					winnerRound = "Empate";
					var info = {
						BluePoints: parseInt(bluePoints),
						RedPoints: parseInt(redPoints),
						Points: bluePoints + "-" + redPoints,
						WinnerBattle: winnerBattle,
						WinnerRound: winnerRound,
						WinnerPic: winnerPic
					}
					
					database.ref().child('RoundVoteInfo').update(info);
				}
			} else if (nJudges === 3 && nVotes3 === 3) {
				if (nBlues > nReds) {
					bluePoints++;
					winnerRound = "" + blueCorner;
					if (((nRounds/2) < bluePoints) || ((nRounds/2) < redPoints) || (bluePoints + redPoints === nRounds)) {
						if (bluePoints > redPoints) {
							winnerBattle = "" + blueCorner;
							winnerPic = "" + blueCornerImg;
						}
						else if (redPoints > bluePoints) {
							winnerBattle = "" + redCorner;
							winnerPic = "" + redCornerImg;
						}
					}
					var info = {
						BluePoints: parseInt(bluePoints),
						RedPoints: parseInt(redPoints),
						Points: bluePoints + "-" + redPoints,
						WinnerBattle: winnerBattle,
						WinnerRound: winnerRound,
						WinnerPic: winnerPic
					}
					
					database.ref().child('RoundVoteInfo').update(info);
				} else if (nReds > nBlues) {
					redPoints++;
					winnerRound = "" + redCorner;
					if (((nRounds/2) < bluePoints) || ((nRounds/2) < redPoints) || (bluePoints + redPoints === nRounds)) {
						if (bluePoints > redPoints) {
							winnerBattle = "" + blueCorner;
							winnerPic = "" + blueCornerImg;
						}
						else if (redPoints > bluePoints) {
							winnerBattle = "" + redCorner;
							winnerPic = "" + redCornerImg;
						}
					}
					var info = {
						BluePoints: parseInt(bluePoints),
						RedPoints: parseInt(redPoints),
						Points: bluePoints + "-" + redPoints,
						WinnerBattle: winnerBattle,
						WinnerRound: winnerRound,
						WinnerPic: winnerPic
					}
					
					database.ref().child('RoundVoteInfo').update(info);
				} else {
					winnerRound = "Empate";
					var info = {
						BluePoints: parseInt(bluePoints),
						RedPoints: parseInt(redPoints),
						Points: bluePoints + "-" + redPoints,
						WinnerBattle: winnerBattle,
						WinnerRound: winnerRound,
						WinnerPic: winnerPic
					}
					
					database.ref().child('RoundVoteInfo').update(info);
				}
			}
		}
	});
});

/*	-----	-----	-----	-----	BUTTON ACTIONS	-----	-----	-----	-----	*/
$(document).ready(function() {
	/*	Judge #1	-----------------------------------------------------------------	*/
	$('#vote1').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote1: "BLANK",
			Flag1 : 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	Judge #2	-----------------------------------------------------------------	*/
	$('#vote2').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote2: "BLANK",
			Flag2 : 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	Judge #3	-----------------------------------------------------------------	*/
	$('#vote3').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote3: "BLANK",
			Flag3 : 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	Judge #4	-----------------------------------------------------------------	*/
	$('#vote4').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote4: "BLANK",
			Flag4 : 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	Judge #5	-----------------------------------------------------------------	*/
	$('#vote5').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote5: "BLANK",
			Flag5 : 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});

	/*	Next Round	-----------------------------------------------------------------	*/
	$('#buttonNextRound').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote1: "BLANK",
			Vote2: "BLANK",
			Vote3: "BLANK",
			Vote4: "BLANK",
			Vote5: "BLANK",
			Flag1 : 0,
			Flag2 : 0,
			Flag3 : 0,
			Flag4 : 0,
			Flag5 : 0,
			BluePoints: parseInt($('#blueCornerPoints').val()),
			RedPoints: parseInt($('#redCornerPoints').val()),
			Points: $('#blueCornerPoints').val() + "-" + $('#redCornerPoints').val(),
			Round: 1 + parseInt($('#blueCornerPoints').val()) + parseInt($('#redCornerPoints').val()),
			WinnerBattle: "",
			WinnerRound: "",
			WinnerPic: "",
			Dialog: 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	Next Battle	-----------------------------------------------------------------	*/
	$('#buttonNextBattle').on('click', function (evt) {
		var database = firebase.database();
		
		var numJudgesRadio = $("input[name='numJudges']:checked").val();
		
		var info = {
			Vote1: "BLANK",
			Vote2: "BLANK",
			Vote3: "BLANK",
			Vote4: "BLANK",
			Vote5: "BLANK",
			Flag1 : 0,
			Flag2 : 0,
			Flag3 : 0,
			Flag4 : 0,
			Flag5 : 0,
			BlueCorner: $('#blueCornerName').val(),
			BluePoints: 0,
			BlueCornerImage: $('#blueCornerImage').val(),
			RedCorner: $('#redCornerName').val(),
			RedPoints: 0,
			RedCornerImage: $('#redCornerImage').val(),
			Tournament: $('#tournamentName').val(),
			Points: "0-0",
			Round: 1,
			WinnerBattle: "",
			WinnerRound: "",
			WinnerPic: "",
			NJudges: (numJudgesRadio != null ? parseInt(numJudgesRadio) : 0),
			NRounds: parseInt($('#numRounds').val()),
			Dialog: 0
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	RESET	---------------------------------------------------------------------	*/
	$('#buttonReset').on('click', function (evt) {
		var database = firebase.database();
		
		var numJudgesRadio = $("input[name='numJudges']:checked").val();
		
		var info = {
			Vote1: "BLANK",
			Vote2: "BLANK",
			Vote3: "BLANK",
			Vote4: "BLANK",
			Vote5: "BLANK",
			Flag1 : 1,
			Flag2 : 1,
			Flag3 : 1,
			Flag4 : 1,
			Flag5 : 1,
			BlueCorner: "---",
			BluePoints: 0,
			BlueCornerImage: "dummyBlue",
			RedCorner: "---",
			RedPoints: 0,
			RedCornerImage: "dummyRed",
			Tournament: "---",
			Points: "0-0",
			Round: 1,
			WinnerBattle: "",
			WinnerRound: "",
			WinnerPic: "",
			NJudges: (numJudgesRadio != null ? parseInt(numJudgesRadio) : 0),
			NRounds: parseInt($('#numRounds').val()),
			Dialog: 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	CLOSE	---------------------------------------------------------------------	*/
	$('#buttonClose').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Flag1: 1,
			Flag2: 1,
			Flag3: 1,
			Flag4: 1,
			Flag5: 1,
			Dialog: 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
});
