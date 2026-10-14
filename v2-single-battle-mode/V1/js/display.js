$(window).on("load", function () {
    var rootRef = firebase.database().ref().child('RoundVoteInfo');
	
	rootRef.on('value', function(snapshot){
		$('#judge1').text(snapshot.child('Judge1').val());
		$('#judge2').text(snapshot.child('Judge2').val());
		$('#judge3').text(snapshot.child('Judge3').val());
		$('#judge4').text(snapshot.child('Judge4').val());
		$('#judge5').text(snapshot.child('Judge5').val());
		
		var vote1 = snapshot.child('Vote1').val();
		switch (vote1) {
			case "BLUE": $('#vote1').attr("src","assets/image/blue.png"); break;
			case "DRAW": $('#vote1').attr("src","assets/image/draw.png"); break;
			case "RED": $('#vote1').attr("src","assets/image/red.png"); break;
			case "BLANK": $('#vote1').attr("src","assets/image/default.png"); break;
		}
		var vote2 = snapshot.child('Vote2').val();
		switch (vote2) {
			case "BLUE": $('#vote2').attr("src","assets/image/blue.png"); break;
			case "DRAW": $('#vote2').attr("src","assets/image/draw.png"); break;
			case "RED": $('#vote2').attr("src","assets/image/red.png"); break;
			case "BLANK": $('#vote2').attr("src","assets/image/default.png"); break;
		}
		var vote3 = snapshot.child('Vote3').val();
		switch (vote3) {
			case "BLUE": $('#vote3').attr("src","assets/image/blue.png"); break;
			case "DRAW": $('#vote3').attr("src","assets/image/draw.png"); break;
			case "RED": $('#vote3').attr("src","assets/image/red.png"); break;
			case "BLANK": $('#vote3').attr("src","assets/image/default.png"); break;
		}
		var vote4 = snapshot.child('Vote4').val();
		switch (vote4) {
			case "BLUE": $('#vote4').attr("src","assets/image/blue.png"); break;
			case "DRAW": $('#vote4').attr("src","assets/image/draw.png"); break;
			case "RED": $('#vote4').attr("src","assets/image/red.png"); break;
			case "BLANK": $('#vote4').attr("src","assets/image/default.png"); break;
		}
		var vote5 = snapshot.child('Vote5').val();
		switch (vote5) {
			case "BLUE": $('#vote5').attr("src","assets/image/blue.png"); break;
			case "DRAW": $('#vote5').attr("src","assets/image/draw.png"); break;
			case "RED": $('#vote5').attr("src","assets/image/red.png"); break;
			case "BLANK": $('#vote5').attr("src","assets/image/default.png"); break;
		}

		$('#blueCorner').text(snapshot.child('BlueCorner').val());
		$('#bluePoints').text(snapshot.child('BluePoints').val());
		$('#redCorner').text(snapshot.child('RedCorner').val());
		$('#redPoints').text(snapshot.child('RedPoints').val());

		$('#tournament').text(snapshot.child('Tournament').val());
		$('#pointNumbers').text(snapshot.child('Points').val());
		$('#roundNumber').text("Round "+snapshot.child('Round').val());
		$('#winnerRound').text(snapshot.child('WinnerRound').val());
		$('#winnerBattle').text(snapshot.child('WinnerBattle').val());
		
		var dialog = snapshot.child('Dialog').val();
		if (dialog === 1) {
			$('.dialogContainer').show();
		} else {
			$('.dialogContainer').hide();
		}
		
		var winner = snapshot.child('WinnerBattle').val();
		if (!winner.trim()) {
			$('.winnerContainer').hide();
		} else {
			setTimeout(function() { $('.winnerContainer').show(); }, 2500);
		}
	});
});
