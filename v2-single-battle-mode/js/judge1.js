/*	-----	-----	-----	-----	FIREBASE LOAD	-----	-----	-----	-----		*/
$(window).on("load", function () {
    var rootRef = firebase.database().ref().child('RoundVoteInfo');
	
	rootRef.on('value', function(snapshot){
		/*	-----	-----	-----	-----	Judges		-----	-----	-----	-----	*/
		$('#judgeName').val(snapshot.child('Judge1').val());
		
		var flag = snapshot.child('Flag1').val();
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
			Judge1: $('#judgeName').val()
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	BLUE	--------------------------------------------------------------------	*/
	$('#buttonVoteBlue').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote1: "BLUE",
			Flag1 : 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	DRAW	--------------------------------------------------------------------	*/
	$('#buttonVoteDraw').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote1: "DRAW",
			Flag1 : 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
	/*	RED		--------------------------------------------------------------------	*/
	$('#buttonVoteRed').on('click', function (evt) {
		var database = firebase.database();
		
		var info = {
			Vote1: "RED",
			Flag1 : 1
		}
		
		database.ref().child('RoundVoteInfo').update(info);
	});
});
