<?php session_start(); require("functions.php");?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <title>RATP | DasniloYT</title>
</head>

<body>
    <div class="navbar">
        <span id="title">RATP</span>
    </div>
    <form action="index.php" method="post">
        <label>Mode :
            <select name="type" name="type" onchange="document.getElementById('line').textContent = '';document.getElementById('stop').textContent = '';this.form.submit();">
                <option selected></option>
                <?php get_type(); ?>
            </select>
        </label><br>
        <label>Ligne :
            <select name="line" id="line" onchange="document.getElementById('stop').textContent = '';this.form.submit();">
                <option selected></option>
                <?php if(isset($_POST['type'])){get_lines($_POST['type']);} ?>
            </select>
        </label><br>
        <label>Arrêt :
            <select name="stop" id="stop" onchange="this.form.submit();">
                <option selected></option>
                <?php if(isset($_POST['line'])){get_stops($_POST['line']);}?>
            </select>
        </label>
    </form>
    <?php
        if(isset($_POST['line']) && isset($_POST['stop']) && !preg_match("/^$|\s+/", $_POST['line']) && !preg_match("/^$|\s+/", $_POST['stop'])){
            get_horaires($_POST['stop'], $_POST['line']);
        }
    
    ?>
</body>

</html>