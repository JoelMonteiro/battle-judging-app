/*	-----	-----	-----	-----	FIREBASE LOAD	-----	-----	-----	-----		*/
$(window).on("load", function () {
    var rootRef = firebase.database().ref().child('RoundVoteInfo');
	
	rootRef.on('value', function(snapshot){
		/*	-----	-----	-----	-----	Judges		-----	-----	-----	-----	*/
		$('#judgeName').val(snapshot.child('Judge3').val());
		
		var flag = snapshot.child('Flag3').val();
		if (flag != null) {
			if (flag === 1) {
				$('.dialogContainer').show();
			} else {
				$('.dialogContainer').hide();
			}
		}
	});
});

/*	-----	-----	-----	-----	BUTTON ACTIONS	-----	-----	-----	-----		*/
$(document).ready(function() {
	/*	Send Button	-----------------------------------------------------------------	*/
	$('#buttonSendJudgeName').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Judge3: $('#judgeName').val()
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	BLUE	--------------------------------------------------------------------	*/
	$('#buttonVoteBlue').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote3: "BLUE",
			Flag3 : 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	DRAW	--------------------------------------------------------------------	*/
	$('#buttonVoteDraw').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote3: "DRAW",
			Flag3 : 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	RED		--------------------------------------------------------------------	*/
	$('#buttonVoteRed').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote3: "RED",
			Flag3 : 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
});
