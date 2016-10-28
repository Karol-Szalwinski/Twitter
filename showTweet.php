<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//Ustalamy id i name zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();
$errors = [];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pokaż Tweeta</title>
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
            <fieldset class="message">
                <legend><h3> Tweet:</h3></legend>
                <?php
                //sprawdzam czy tid w linku jest przesłany i poprawny
                if (isset($_GET['tid']) && is_numeric($_GET['tid'])) {
                    $tid = $_GET['tid'];
                    //Sprawdzam czy jest w bazie
                    if ($tweet = Tweet::loadTweetById($conn, $tid)) {
                        //wyświetlam tweeta
                        $tweet->showTweet($conn);
                        //sprawdzam czy został dodany jakiś komentarz
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
                                isset($_POST['comment-text']) &&
                                strlen(trim($_POST['comment-text'])) > 0) {
                            $newComment = substr(trim($_POST['comment-text']), 0, 60);
                            $comment = new Comment;
                            $comment->setComment($newComment)
                                    ->setUserId($loggedUserId)->setTweetId($tid);
                            $comment->saveToDB($conn);
                        }
                    } else {
                        $errors[] = 'Tweet nie został znaleziony';
                    }
                } else {
                    $errors[] = 'Grrr... coś kombinujesz z adresem url... Nieładnie!';
                }
                printErrors($errors);
                ?>
            </fieldset>
            <form action=# method="POST">
                <fieldset class="message">
                    <legend><h3>Dodaj swój komentarz do tweeta:</h3></legend>
                    <label>                 
                        <input class="input" type="text" maxlength="60" 
                               name="comment-text" placeholder="Skomentuj (maksymalnie 60 znaków)"><br>
                        <input class='button'  type="submit" value="Dodaj">
                    </label>
                </fieldset>
            </form>
            <h3> Komentarze:</h3>
            <?php
            //jeżeli mamy poprawny tid i jest o tym id tweet w bazie to 
            //wyświetlam do niego komentarze
            if (isset($tid) && $tweet != null) {
                $tweetAllComments = Comment::loadCommentByTweetId($conn, $tid);
                foreach ($tweetAllComments as $comment) {
                    $comment->showComment($conn);
                }
            }
            ?>
        </main>

        <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>   
        </footer>
    </body>
</html>
