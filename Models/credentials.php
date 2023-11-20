<?php
    $mdp = json_decode(file_get_contents("pass.json"), true)['pgsql_pass'];
    $dsn = "pgsql:host=localhost;dbname=ratp_app";
    $login = "omar";
?>