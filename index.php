<?php
ob_start();
require_once 'src/requiredFiles.php';

//Ustalamy id i name zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();

//Jeśli został dodany tweet metodą post waliduję go i zapisuję w bazie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tweet-text']) && strlen(trim($_POST['tweet-text'])) > 0) {
        $newTweet = substr(trim($_POST['tweet-text']), 0, 140);
        $tweet = new Tweet;
        $tweet->setTweet($newTweet)->setUserId($loggedUserId);
        $tweet->saveToDB($conn);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Twitter</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css" type="text/css" />
    </head>
    <body>

        <!-----------Nagłówek z menu-------------->
        <header>
            <?php require_once (dirname(__FILE__) . '/src/header.php'); ?>
        </header>

        <!—-----------Panel boczny --------------->
        <aside>
            <?php
            $sidebarUser = $loggedUser;
            require_once (dirname(__FILE__) . '/src/sidebar.php');
            ?>
        </aside>

        <!—-----------Główna treść --------------->
        <main>
            <div>
                <form action=# method="POST">
                    <fieldset class="message">
                        <legend><h3>Dodaj tweeta:</h3></legend>
                        <input class='input' type="text" maxlength="140"
                               name="tweet-text" placeholder="Co się dzieje?"><br>
                        <input class='button'  type="submit" value="Tweetnij">
                    </fieldset>
                </form>
            </div>
            <?php
            //Wyświetlam wszystkie tweety wg daty
            $allTweets = Tweet::loadAllTweets($conn);
            foreach ($allTweets as $tweet) {
                $tweet->showTweet($conn);
            }
            ?>
        </main>

        <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>   
        </footer>

    </body>
</html>