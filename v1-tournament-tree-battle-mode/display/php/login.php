<?php

    session_start();
    require_once 'init.php';

    // Login com prepared statement + password_hash/password_verify.
    // (A versão original concatenava $_POST['txtUser'] diretamente no SQL - injeção de SQL - e usava sha1.)
    // Para criar um utilizador:  php -r "echo password_hash('a_tua_password', PASSWORD_DEFAULT);"

    $user = isset($_POST['txtUser']) ? trim($_POST['txtUser']) : '';
    $pass = isset($_POST['txtPass']) ? $_POST['txtPass'] : '';

    if ($user === '' || $pass === '') {
        header("location: ../BackOffice.php?erro3"); // Campos em branco!
        exit;
    }

    $db = new Init();
    $sttm = $db->connection()->prepare("SELECT login, pass FROM users WHERE login = ?");
    $sttm->bind_param("s", $user);

    if (!$sttm->execute()) {
        header("location: ../BackOffice.php?error"); // Erro inesperado!
        exit;
    }

    $row = $sttm->get_result()->fetch_assoc();

    // A mesma mensagem para "utilizador não existe" e "password errada", para não revelar que logins existem
    if ($row && password_verify($pass, $row['pass'])) {
        session_regenerate_id(true);
        $_SESSION['User'] = $row['login'];
        header("location: ../BackOffice.php?success");
    } else {
        header("location: ../BackOffice.php?erro1"); // Username ou Password incorrectos!
    }
    exit;

?>
