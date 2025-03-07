<?php

    require '../_PHP/connection.php';
    include '../_PHP/generic_functions.php';



    //isset($_GET['user']) && is_numeric($_GET['user']) - not using this method as username can be VARCHAR, meaning numbers as well.
    if (isset($_GET['user'])) {
        if(!is_numeric($_GET['user'])){
            if(is_string($_GET['user'])){
                $username = $_GET['user'];
                $user_id = $conn->query("SELECT user_id FROM user WHERE username='$username'")->fetch_assoc()['user_id'];
            } else passTo('../home/',['msg'],["Couldn't find user. <br> Passed user neither a string nor a number."]);
        } else {
            $user_id = $_GET['user'];
            $username = $conn->query("SELECT username FROM user WHERE user_id=$user_id")->fetch_assoc()['username'];
        }
    } else if (isset($_GET['username'])) {
        // despite username being a UUID, it's less scalable, performance wide, as the length of one can be up to 32 varchars.
        $username = $_GET['username'];
        $user_id = $conn->query("SELECT user_id FROM user WHERE username='$username'")->fetch_assoc()['user_id'];
    } else {
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
    }

    if(!$username || !$user_id) passTo('../home/',['msg'],["User not found."]);


    $ratings = new stdClass();
    $ratings->albums = new stdClass();
    $ratings->artists = new stdClass();

    // ARTIST RATINGS
    $ratings->artists->table = $conn->query('SELECT * FROM userRating_artist WHERE user_id=' . $user_id . ' ORDER BY rating DESC');
    $ratings->artists->count = 0;
    while ($row = $ratings->artists->table->fetch_assoc()) {
        $rating = $row['rating'];
        $artist_id = $row['artist_id'];
        $artist = $conn->query('SELECT firstName, lastName FROM artist WHERE artist_id=' . $artist_id)->fetch_assoc();
        $artistName = $artist['firstName'] . ' ' . $artist['lastName'];
        $ratings->artists->all[$ratings->artists->count] = ['artist_id' => $artist_id, 'artistName' => $artistName, 'rating' => $rating];
        $ratings->artists->count++;
    }


    // ALBUM RATINGS
    $ratings->albums->table = $conn->query('SELECT * FROM userRating_album WHERE user_id=' . $user_id . ' ORDER BY rating DESC');
    $ratings->albums->count = 0;
    while ($row = $ratings->albums->table->fetch_assoc()) {
        $rating = $row['rating'];
        $album_id = $row['album_id'];
        $albumTitle = $conn->query('SELECT title FROM album WHERE album_id=' . $album_id)->fetch_assoc()['title'];
        $ratings->albums->all[$ratings->albums->count] = ['album_id' => $album_id, 'title' => $albumTitle, 'rating' => $rating];
        $ratings->albums->count++;
    }


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> <?php echo $username ?> | City Pop Lookup </title>


    <?php include '../_HTML/BTS_universalHeaderLinks.html' ?>


    <script src="../_JS/universal_menu.js"></script>
    <link href="../_CSS/universal_menu.css" rel="stylesheet">
    <script src="../_JS/fitty.min.js"></script>
    <link href="../_CSS/subpages.css" rel="stylesheet">
    <link href="stylesheet.css" rel="stylesheet">


</head>
<body>


<!-- header -->
<?php include '../_HTML/UI_header.html' ?>


<!-- DOCUMENT WRAPPER -->
<div id="documentWrapper" class="container column" style="margin-top: 125px">


    <!-- stuff overlapping the header (the header box uses clip-path, which also cuts child elements, therefore this is outside of the main header container.) -->
    <?php include '../_HTML/UI_onHeader.html' ?>

    <span><br></span>


    <!-- OPTIONAL MESSAGE BAR -->
    <?php include '../_HTML/UI_msg.php' ?>


    <!-- ARTIST CONTAINER -->
    <div id="artistContainer" class="primary">

        <div class="secondary" id="artistTitleDiv">
            <h1 class="title anim_text-expand" id="editable1" style="font-size: 35pt">
                <?php echo ucwords($username) ?>'s ratings</h1>
        </div>

        <!-- RATINGS -->
        <div class="container row alignLeft">

            <div id="artistRatings" style="max-width: 33vw" class="secondary">
                <h1 id="editable2" class="fancyFont"> Artists </h1>
                <hr>

                <?php
                    if (isset($ratings->artists->all)) {
                        echo '<table class="container">
                                    <tr class="container">
                                        <th style="border: none">Artist</th>
                                        <th style="border: none">Rating</th>
                                    </tr>';
                        foreach ($ratings->artists->all as $i => $artist) {
                            echo '<tr class="container" style="background-color: transparent !important"><td>';
                            echo '<button class="minimalistButton noPadding" style="color: black; font-size: 12pt;" onclick="window.location.href=\'../artist/?a=' . $artist['artist_id'] . '\'">';
                            echo '<div class="container row" style="justify-items: center; align-items: center; justify-content: center; align-content"> ' . $artist['artistName'];
                            echo '</td><td class="table_rating">';
                            echo '<p style="color: black; margin-left: 1em">' . floor($artist['rating']) . ' / 5</p></div></button>';
                            echo '</td></tr>';
                        }
                        echo '</table>';
                    } else echo '<h2 style="margin: 25px"> No artists rated yet </h2>'
                ?>

            </div>
            <div id="albumRatings" style="max-width: 35vw" class="secondary">
                <h1 id="editable2" class="fancyFont"> Albums </h1>
                <hr>
                <?php
                    if (isset($ratings->albums->all)) {
                        echo '<table class="container">
                                    <tr class="container">
                                        <th style="border: none">Artist</th>
                                        <th style="border: none">Rating</th>
                                    </tr>';
                        foreach ($ratings->albums->all as $i => $album) {
                            echo '<tr class="container" style="background-color: transparent !important"><td>';
                            echo '<button class="minimalistButton noPadding" style="color: black; font-size: 12pt;" onclick="window.location.href=\'../album/?a=' . $album['album_id'] . '\'">';
                            echo '<div class="container row" style="justify-items: center; align-items: center; justify-content: center; align-content"> ' . $album['title'];
                            echo '</td><td class="table_rating">';
                            echo '<p style="color: black; margin-left: 1em">' . floor($album['rating']) . ' / 5</p></div></button>';
                            echo '</td></tr>';
                        }
                        echo '</table>';
                    } else echo '<h2 style="margin: 25px"> No albums rated yet </h2>'
                ?>
            </div>
        </div>

    </div>




    <!-- footer
    <?php include '../_HTML/UI_footer.html' ?>
    -->



</div> <!-- end of document wrapper -->
</body>
</html>
