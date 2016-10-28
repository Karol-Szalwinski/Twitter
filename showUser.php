<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//Ustalamy id i name zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();
$errors = [];

// Jeżeli dostaliśmy poprawny uid w adresie
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
        $uid = $_GET['uid'];
        //Jeżeli user o tym uid jest w bazie
        if ($user = User::loadUserById($conn, $uid)) {
            $userName = $user->getUsername();
        } else {
            $errors[] = 'Nie ma takiego użytkownika w bazie.';
        }
    } else {
        $errors[] = 'Grrr... coś kombinujesz z adresem url... Nieładnie!';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Użytkownik</title>
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
            // Gdy jest user w bazie to w sidebarze są jego dane, jeśli nie to nasze
            $sidebarUser = (isset($userName)) ? $user : $loggedUser;
            require_once (dirname(__FILE__) . '/src/sidebar.php');
            ?>
        </aside>

        <!—-----------Główna treść --------------->
        <main>
            
            <?php
            printErrors($errors);
            //sprawdzam czy user jest w bazie i wyświetlam jego tweety 
            if (isset($userName)) {
                if ($userAllTweets = Tweet::loadTweetByUserId($conn, $uid)) {
                    echo '<h3>Tweety użytkownika:</h3>';
                    foreach ($userAllTweets as $tweet) {
                        $tweet->showTweet($conn);
                    }
                } else {
                    echo 'Użytkownk nie posiada jeszcze żadnych tweetów.';
                }
            }
            ?>
        </main>

        <!—---------------Stopka------------------->
        <footer>
<?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>  
        </footer>
    </body>
</html>

