<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Battle App - BackOffice</title>
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
		<link rel="stylesheet" href="css/associate.css" />

		<!--Scripts-->
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/associate.js"></script>
		
	</head>
    <body class="formFont" style="background-color:#d4e7ff;">
        
		<?php
            session_start();

            if (!isset($_SESSION['User'])) {
                echo '<div style="margin-top: 20px; text-align: center;"><b style="color: red";>Area reservada a membros!</b></div>';
				echo '<div style="text-align: center; margin-top: 100px;"><button id="btnBacker" class="btn btn-info btn-lg"> Voltar </button></div>';
			} else {
		?>
				<table class="table_cmb">
					<tr>
						<td class="col col-md-4"></td>
						<td class="col col-md-4">
							<select id="cmbTournament">
								<option value="-1" disabled selected>Torneios</option>
								<?php
									require_once 'includes/DbOperations.php';

									$response = array();
									$db = new DbOperations();
									
									$response = $db->selectAllTournaments();

									while ($row = $response->fetch_assoc()) {
										unset($fldId, $name);
										$fldId = $row['fldId'];
										$name = $row['nome'];
										echo '<option value="'.$fldId.'">'.$name.'</option>';
									}
								?>
							</select>
						</td>
						<td class="col col-md-4"></td>
					</tr>
				</table>
				
				<main id="tournament">
					<ul class="round round-1">
						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>1</span><select id="competitor1"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>2</span><select id="competitor2"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>3</span><select id="competitor3"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>4</span><select id="competitor4"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>5</span><select id="competitor5"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>6</span><select id="competitor6"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>7</span><select id="competitor7"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>8</span><select id="competitor8"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>9</span><select id="competitor9"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>10</span><select id="competitor10"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>11</span><select id="competitor11"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>12</span><select id="competitor12"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>13</span><select id="competitor13"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>14</span><select id="competitor14"></select></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>15</span><select id="competitor15"></select></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>16</span><select id="competitor16"></select></li>

						<li class="spacer">&nbsp;</li>
					</ul>
					<ul class="round round-2">
						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>

						<li class="spacer">&nbsp;</li>
					</ul>
					<ul class="round round-3">
						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>

						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>

						<li class="spacer">&nbsp;</li>
					</ul>
					<ul class="round round-4">
						<li class="spacer">&nbsp;</li>
						
						<li class="game game-top"><span>#</span></li>
						<li class="game game-spacer">&nbsp;</li>
						<li class="game game-bottom"><span>#</span></li>
						
						<li class="spacer">&nbsp;</li>
					</ul>		
				</main>
				
				<table class="divButtons">
					<tr>
						<td class="col-md-5">
							<div style="text-align: left;"><button id="btnBack" class="btn btn-info btn-lg"> Voltar </button></div>
						</td>
						<td class="col-md-5">
							<div style="text-align: right;"><button id="btnConfirm" class="btn btn-info btn-lg"> Confirmar </button></div>
						</td>
					</tr>
				</table>
		
		<?php
            }
		?>
		
        <footer class="footer">
            <p>
				Battle App © 2018
			</p>
        </footer>
		
		<script type="text/javascript">
			$('#btnBack').on('click', function (evt) {
				window.location.href = "BackOffice.php";
			});
			$('#btnBacker').on('click', function (evt) {
				window.location.href = "BackOffice.php";
			});
			
			$('#cmbTournament').on('change', function (evt) {
				var $tournamentId = $(this).val();
				
				$.ajax({data: {tournamentID: $tournamentId},url:'php/getAllCompetitors.php',success:function(result) {
					$competitors = jQuery.parseJSON(result);

					$('#competitor1').empty();
					$('#competitor2').empty();
					$('#competitor3').empty();
					$('#competitor4').empty();
					$('#competitor5').empty();
					$('#competitor6').empty();
					$('#competitor7').empty();
					$('#competitor8').empty();
					$('#competitor9').empty();
					$('#competitor10').empty();
					$('#competitor11').empty();
					$('#competitor12').empty();
					$('#competitor13').empty();
					$('#competitor14').empty();
					$('#competitor15').empty();
					$('#competitor16').empty();
					
					$('#competitor1').append('<option value=0>---</option>');
					$('#competitor2').append('<option value=0>---</option>');
					$('#competitor3').append('<option value=0>---</option>');
					$('#competitor4').append('<option value=0>---</option>');
					$('#competitor5').append('<option value=0>---</option>');
					$('#competitor6').append('<option value=0>---</option>');
					$('#competitor7').append('<option value=0>---</option>');
					$('#competitor8').append('<option value=0>---</option>');
					$('#competitor9').append('<option value=0>---</option>');
					$('#competitor10').append('<option value=0>---</option>');
					$('#competitor11').append('<option value=0>---</option>');
					$('#competitor12').append('<option value=0>---</option>');
					$('#competitor13').append('<option value=0>---</option>');
					$('#competitor14').append('<option value=0>---</option>');
					$('#competitor15').append('<option value=0>---</option>');
					$('#competitor16').append('<option value=0>---</option>');
					
					for (var i = 0; i < $competitors.length; i++) {
						$('#competitor1').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor2').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor3').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor4').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor5').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor6').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor7').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor8').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor9').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor10').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor11').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor12').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor13').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor14').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor15').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
						$('#competitor16').append('<option value='+$competitors[i].fldID+'>'+$competitors[i].nome+'</option>');
					}
				}});
			});
			
			$('#btnConfirm').on('click', function (evt) {
				var list = new Array();
				var lista = new Array();
				var $Torneio = $('#cmbTournament').val();
				var $Competitor1 = $('#competitor1').val();		if ($Competitor1 != null && $Competitor1 != 0) { list.push($Competitor1); } lista.push($Competitor1);
				var $Competitor2 = $('#competitor2').val();		if ($Competitor2 != null && $Competitor2 != 0) { list.push($Competitor2); } lista.push($Competitor2);
				var $Competitor3 = $('#competitor3').val();		if ($Competitor3 != null && $Competitor3 != 0) { list.push($Competitor3); } lista.push($Competitor3);
				var $Competitor4 = $('#competitor4').val();		if ($Competitor4 != null && $Competitor4 != 0) { list.push($Competitor4); } lista.push($Competitor4);
				var $Competitor5 = $('#competitor5').val();		if ($Competitor5 != null && $Competitor5 != 0) { list.push($Competitor5); } lista.push($Competitor5);
				var $Competitor6 = $('#competitor6').val();		if ($Competitor6 != null && $Competitor6 != 0) { list.push($Competitor6); } lista.push($Competitor6);
				var $Competitor7 = $('#competitor7').val();		if ($Competitor7 != null && $Competitor7 != 0) { list.push($Competitor7); } lista.push($Competitor7);
				var $Competitor8 = $('#competitor8').val();		if ($Competitor8 != null && $Competitor8 != 0) { list.push($Competitor8); } lista.push($Competitor8);
				var $Competitor9 = $('#competitor9').val();		if ($Competitor9 != null && $Competitor9 != 0) { list.push($Competitor9); } lista.push($Competitor9);
				var $Competitor10 = $('#competitor10').val();		if ($Competitor10 != null && $Competitor10 != 0) { list.push($Competitor10); } lista.push($Competitor10);
				var $Competitor11 = $('#competitor11').val();		if ($Competitor11 != null && $Competitor11 != 0) { list.push($Competitor11); } lista.push($Competitor11);
				var $Competitor12 = $('#competitor12').val();		if ($Competitor12 != null && $Competitor12 != 0) { list.push($Competitor12); } lista.push($Competitor12);
				var $Competitor13 = $('#competitor13').val();		if ($Competitor13 != null && $Competitor13 != 0) { list.push($Competitor13); } lista.push($Competitor13);
				var $Competitor14 = $('#competitor14').val();		if ($Competitor14 != null && $Competitor14 != 0) { list.push($Competitor14); } lista.push($Competitor14);
				var $Competitor15 = $('#competitor15').val();		if ($Competitor15 != null && $Competitor15 != 0) { list.push($Competitor15); } lista.push($Competitor15);
				var $Competitor16 = $('#competitor16').val();		if ($Competitor16 != null && $Competitor16 != 0) { list.push($Competitor16); } lista.push($Competitor16);

				if (!hasDuplicates(list)) {
					if ($Torneio != null && $Torneio != 0) {
						for (var i = 0; i < 15; i+=2) {
								$blueCorner = $('#competitor' + (i+1) + ' option:selected').text();
								$redCorner = $('#competitor' + (i+2) + ' option:selected').text();
								$blueCornerID = $('#competitor' + (i+1)).val();
								$redCornerID = $('#competitor' + (i+2)).val();
								debugger;
								runAjax($blueCorner, $redCorner, $Torneio, i, $blueCornerID, $redCornerID);
						}

						$.ajax({data: {tournamentID: $Torneio},url:'php/updateTournamentReady.php',type:"post",success:function(result) { } });
						
						if ($Competitor1 != null && $Competitor1 != 0)
							$.ajax({data: {ID: $Competitor1, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });

						if ($Competitor2 != null && $Competitor2 != 0)
							$.ajax({data: {ID: $Competitor2, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor3 != null && $Competitor3 != 0)
							$.ajax({data: {ID: $Competitor3, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor4 != null && $Competitor4 != 0)
							$.ajax({data: {ID: $Competitor4, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor5 != null && $Competitor5 != 0)
							$.ajax({data: {ID: $Competitor5, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor6 != null && $Competitor6 != 0)
							$.ajax({data: {ID: $Competitor6, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor7 != null && $Competitor7 != 0)
							$.ajax({data: {ID: $Competitor7, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor8 != null && $Competitor8 != 0)
							$.ajax({data: {ID: $Competitor8, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor9 != null && $Competitor9 != 0)
							$.ajax({data: {ID: $Competitor9, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor10 != null && $Competitor10 != 0)
							$.ajax({data: {ID: $Competitor10, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor11 != null && $Competitor11 != 0)
							$.ajax({data: {ID: $Competitor11, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor12 != null && $Competitor12 != 0)
							$.ajax({data: {ID: $Competitor12, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor13 != null && $Competitor13 != 0)
							$.ajax({data: {ID: $Competitor13, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor14 != null && $Competitor14 != 0)
							$.ajax({data: {ID: $Competitor14, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor15 != null && $Competitor15 != 0)
							$.ajax({data: {ID: $Competitor15, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						if ($Competitor16 != null && $Competitor16 != 0)
							$.ajax({data: {ID: $Competitor16, Tid: $Torneio},url:'php/updateCompetitorPicked.php',type:"post",success:function(result) { } });
						
						$(document).ajaxStop(function () {
							window.location.href = "BackOffice.php";
						});
					}
					else {
						alert("Não há nenhum torneio selecionado!");
					}
				} else {
					alert("Não podem existir valores duplicados!");
				}
			});

			function hasDuplicates(array) {
				var valuesSoFar = [];
				for (var i = 0; i < array.length; ++i) {
					var value = array[i];
					if (valuesSoFar.indexOf(value) !== -1) {
						return true;
					}
					valuesSoFar.push(value);
				}
				return false;
			}
			
			function runAjax(blueCorner, redCorner, Torneio, i, blueCornerID, redCornerID) {
				$.ajax({
					type:"post",
					url:'php/insertRound.php',
					data: {blueCorner: blueCorner, redCorner: redCorner, tournamentID: Torneio, order: i, blueCornerID: blueCornerID, redCornerID: redCornerID},
					success:function(result) {
					} 
				});
			}
			
		</script>
		
    </body>
</html>
