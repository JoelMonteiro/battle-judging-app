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
        <meta name="description" content="Página de BackOffice do Battle App." />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!--Icons-->

        <!--Styles-->
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/w3schools4.min.css" />
        <link rel="stylesheet" href="css/backoffice.css" />

        <!--Scripts-->
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/backoffice.js"></script>
    </head>
    <body style="background-color:#d4e7ff;" class="formFont">

        <?php
            session_start();

            if (!isset($_SESSION['User'])) {
                echo '<div style="margin-top: 20px; text-align: center;"><b style="color: red";>Area reservada a membros!</b></div>';
        ?>
                <div id="divPerformLogin" class="divLogin">
                    <form class="formLogin" method="post" action="php/login.php">
                        <table class="w3-center tableLogin">
                            <tr class="">
                                <td class=""><label class="loginFont">Username: &nbsp; </label></td>
                                <td class=""><input type="text" name="txtUser" class="" required autofocus /></td>
                            </tr>
                            <tr class="">
                                <td class=""><label class="loginFont">Password: &nbsp; </label></td>
                                <td class=""><input type="password" name="txtPass" class="" required /></td>
                            </tr>
                            <tr class="">
                                <td class=""></td>
                                <td class=""><input type="submit" id="btnLogin" class="btnLogin loginFont" value="Login" /></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <?php
                    if(isset($_GET['error']))
                        echo '<div style="margin-top: 20px; text-align: center;"><i style="color: red";>Unexpected Error!</i></div>';
                    elseif(isset($_GET['erro1']))
                        echo '<div style="margin-top: 20px; text-align: center;"><i style="color: red";>Username ou Password incorrectos!</i></div>';
                    elseif(isset($_GET['erro2']))
                        echo '<div style="margin-top: 20px; text-align: center;"><i style="color: red";>Username n&atilde;o consta na BD!</i></div>';
                    elseif(isset($_GET['erro3']))
                        echo '<div style="margin-top: 20px; text-align: center;"><i style="color: red";>Campos em Branco!</i></div>';
                    elseif(isset($_GET['success'])) // && isset($_SESSION['User']))
                        echo '<div style="margin-top: 20px; text-align: center;"><i style="color: green";>Bem vindo! '.$_SESSION['User'].'</i></div>';
                ?>

        <?php
            } else {
                echo '<div style="margin-top: 20px; text-align: center;"><i style="color: green";>Bem vindo! <b>'.$_SESSION['User'].'</b></i></div>';
        ?>
                <div style="text-align: right; margin-right: 20px;"><a href="php/logout.php" class="btn btn-info btn-lg" style="right: 0%"> Logout</a></div>
				<div style="text-align: right; margin-right: 20px;"><button id="btnAssociacao" class="btn btn-info btn-lg" style="right: 0%"> Associar</button></div>
				
                <div class="divInsert">
                    <table class="w3-center">
                        <tr class="">
                            <td class="col col-md-2"></td>
                            <td class="col col-md-2"><button id="btnInsertTournament" class="btn">Inserir Torneio</button></td>
                            <td class="col col-md-4"></td>
                            <td class="col col-md-2"><button id="btnInsertCompetitor" class="btn">Inserir Competitor</button></td>
                            <td class="col col-md-2"></td>
                        </tr>
                        <tr class="">
                            <td class="col col-md-4" colspan="2"></td>
                            <td class="col col-md-4">
                                <select id="cmbTournament" class="form-control">
                                    <option value="-1" disabled selected>Torneios</option>
                                    <?php
                                        require_once 'includes/DbOperations.php';

                                        $response = array();
                                        $db = new DbOperations();
                                        
                                        $response = $db->selectAllActiveTournaments();

                                        while ($row = $response->fetch_assoc()) {
                                            unset($fldId, $name);
                                            $fldId = $row['fldId'];
                                            $name = $row['nome'];
                                            echo '<option value="'.$fldId.'">'.$name.'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td class="col col-md-4" colspan="2"></td>
                        </tr>
                    </table>
                </div>

                <div id="divInsertTournament" class="divTournament" hidden>
                    <form method="post" action="php/insertTournament.php">
                        <table class="w3-center">
                            <tr class="">
                                <td class="col col-md-3"></td>
                                <td class="col col-md-6">
                                    <label class="animateInput">
                                        <input type="text" name="txtTournament" id="txtTournament" class="form-control" placeholder="Nome do Torneio" maxlength="20" />
                                        <span>Nome do Torneio</span>
                                    </label>
                                </td>
                                <td class="col col-md-3"></td>
                            </tr>
                            <tr class="">
                                <td class="col col-md-3"></td>
                                <td class="col col-md-6"></td>
                                <td class="col col-md-3"><button id="btnInsertTour" class="btn" type="submit">Inserir</button></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <div id="divInsertCompetitor" class="divCompetitor" hidden>
                    <form method="post" action="php/insertCompetitor.php">
                        <table class="w3-center">
                            <tr class="">
                                <td class="col col-md-3"></td>
                                <td class="col col-md-3">
                                    <label class="animateInput">
                                        <input type="text" name="txtName" id="txtName" placeholder="Nome" class="form-control" />
                                        <span>Nome</span>
                                    </label>
                                </td>
                                <td class="col col-md-3">
                                    <select name="txtGender" id="txtGender" class="form-control">
                                        <option value="-1" disabled selected>Género</option>
                                        <option value="0">Rapaz</option>
                                        <option value="1">Rapariga</option>
                                    </select>
                                </td>
                                <td class="col col-md-3"></td>
                            </tr>
                            <tr class="">
                                <td class="col col-md-3"></td>
                                <td class="col col-md-3"></td>
                                <td class="col col-md-3"></td>
                                <td class="col col-md-3"><button id="btnInsertTrik" class="btn">Inserir</button></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <div id="divTournamentCompetitors" > 
                    <table id="tblResultado">
                        <hr>
                    </table>
                    <label id="lblOK"></label>
                </div><!-- hidden -->

        <?php
            }
                if(isset($_GET['competitor']))
                    echo '<div style="margin-top: 20px; text-align: center;"><i style="color: green";>Competitor Inserido!</i></div>';
                if(isset($_GET['tournament']))
                    echo '<div style="margin-top: 20px; text-align: center;"><i style="color: green";>Torneio Inserido!</i></div>';
				if (isset($_GET['tt']))
					echo '<div style="margin-top: 20px; text-align: center;"><i style="color: green";>Associação Inserida!</i></div>';
        ?>

        <footer class="footer">
            <p>
				Battle App © 2018
			</p>
        </footer>

        <script type="text/javascript">
                $('#btnInsertTournament').on('click', function (evt) {
                    $('#cmbTournament').val('Torneios');
                    $('#divInsertTournament').prop('hidden', false);
                    $('#divInsertCompetitor').prop('hidden', true);
                    $('#divTournamentCompetitors').prop('hidden', true);
                });
                $('#btnInsertCompetitor').on('click', function (evt) {
                    $('#cmbTournament').val('Torneios');
                    $('#divInsertTournament').prop('hidden', true);
                    $('#divInsertCompetitor').prop('hidden', false);
                    $('#divTournamentCompetitors').prop('hidden', true);
                });
                $('#cmbTournament').on('change', function (evt) {
                    var $tournamentId = $(this).val();

                    $("#tblResultado tr").remove(); 
                    
                    $.ajax({data: {tournamentID: $tournamentId},url:'php/getCompetitors.php',success:function(result){
                        
                        $competitors = jQuery.parseJSON(result);
                        $competitor_number = 1;
                        
                        for (var i = 0; i < $competitors.length; i++){
                            var td;
                            var tr = document.createElement('tr');
                            
                            td1 = document.createElement('td');
                            td2 = document.createElement('td');
                            td1.innerHTML = (i + 1);
                            td2.innerHTML = $competitors[i].nome;
                            td1.setAttribute("class", "col col-md-6 td1");
                            td2.setAttribute("class", "col col-md-6 td2");
                            tr.appendChild(td1);
                            tr.appendChild(td2);
                            $('#tblResultado').append(tr);
                        };
                    }});
                    
                    $('#divInsertTournament').prop('hidden', true);
                    $('#divInsertCompetitor').prop('hidden', true);
                    $('#divTournamentCompetitors').prop('hidden', false);
                });
                $('#btnInsertTour').on('click', function (evt) {
                    $('#divInsertTournament').prop('hidden', true);
                });
                $('#btnInsertTrik').on('click', function (evt) {
                    $('#divInsertCompetitor').prop('hidden', true);
                });
				$('#btnAssociacao').on('click', function (evt) {
					window.location.href = "Associacao.php";
				});
                $('#btnReload').on('click', function (evt) {
                    window.location.href = "BackOffice.php";
                });
            </script>

    </body>
</html>
