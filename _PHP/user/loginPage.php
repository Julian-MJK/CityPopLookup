<?php

    //include '../generic_functions.php';

    if (isset($_POST['msg'])) $msg = $_POST['msg'];
    isset($_POST['msgColor']) ? $msgColor = $_POST['msgColor'] : $msgColor = 'var(--red)';

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> City Pop Lookup </title>
    <link href="../../resources/img/textures/vinyl32.ico" rel="icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <script src="../../_JS/jquery-3.4.0.js"></script>
    <script src="../../_JS/oddUtilities.js"></script>
    <link href="../../_CSS/classes.css" rel="stylesheet">
    <link href="../../_CSS/universal_generic.css" rel="stylesheet">
    <link href="../../_CSS/universal_theme.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet">

</head>
<body>
<div class="container column" id="documentWrapper">

    <h1 class="title" style="font-size: 10vh; margin: 4vh 0 0 0;">City Pop</h1>
    <h1 class="title" style="font-size: 10vh; margin: 0 0 4vh 0">Lookup</h1>

    <div class='justifyCenter textAlignCenter pop noBottomPadding' id='addDiv'>
        <form action="login.php" id="main_form" method="post" name="main_form" style="display: block">
            <h1>Login</h1>
            <!-- jeg vet jeg kan bruke <label> her, og flere andre metoder, men jeg foretrekker personlig denne metoden. -->
            <span class="noPadding noMargin">
                <span>
                    <p>Username: </p>
                    <p>Password: </p>
                    <p id="confPassText">Retype: </p>
                </span>
                <span>
                    <input name="username" type="text" required>
                    <input name="password" type="password" required>
                    <input id="confPassPass" name="passwordConfirm" type="password">
                </span>
            </span>
            <button class="fancyButtonPrimary" id='submitBtn' style="margin: 0.75em 0;" type="submit">
                Login
            </button>
            <?php if (isset($msg)) echo '<h2 id="msg" style="color: '. $msgColor .'; margin: 0.5em 0 1.2em 0; max-width: 300px; word-break: break-word" class="anim_shake-horizontal"> ' . $msg . ' </h2>'; ?>
        </form>
    </div>
    <button class="fancyButtonBackground" id='switchBtn' onclick="initRegister()" style="margin-top: 3vh; color: white !important;">
        Don't have an account?
    </button>


</div>
<script>

    $("#confPassText")[0].style.display = 'none';
    $("#confPassPass")[0].style.display = 'none';

    function initRegister() {
        document.main_form.action = "register.php";
        $("#addDiv h1")[0].innerText = "Register";
        $("#confPassText")[0].style.display = 'block';
        $("#confPassPass")[0].style.display = 'block';
        $("#submitBtn")[0].innerText = "Register";
        $("#switchBtn")[0].onclick = function () {initLogin()};
        $("#switchBtn")[0].innerText = "Already got an account?";
        if($(".anim_shake-horizontal")) $(".anim_shake-horizontal")[0].style.display = "none";
    }

    function initLogin() {
        document.main_form.action = "login.php";
        $("#addDiv h1")[0].innerText = "Login";
        $("#confPassText")[0].style.display = 'none';
        $("#confPassPass")[0].style.display = 'none';
        $("#submitBtn")[0].innerText = "Login";
        $("#switchBtn")[0].onclick = function () {initRegister()};
        $("#switchBtn")[0].innerText = "Don't have an account?";
    }

</script>
</body>
</html>
