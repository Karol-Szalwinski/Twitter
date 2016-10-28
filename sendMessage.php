<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//Ustalamy id i name zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();
$errors = [];
//sprawdzam czy tid w linku jest przesłany i poprawny
if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
    $uid = $_GET['uid'];
    if (User::loadUserById($conn, $uid) != null) {
        //sprawdzam czy została wysłana treść
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['message-text']) && strlen(trim($_POST['message-text'])) > 0) {
                $newMessage = $_POST['message-text'];
                $message = new Message;
                $message->setSenderId($loggedUserId)->setRecipientId($uid)
                        ->setMessage($newMessage)->saveToDB($conn);
                $confirm[] = 'Wiadomość została wysłana';
            }
        }
    } else {
        $errors[] = 'Nie ma takiego użytkownika w bazie.';
    }
} else {
    $errors[] = 'Grrr... coś kombinujesz z adresem url... Nieładnie!';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Wyślij wiadomość</title>
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
            <form action=# method="POST">
                <fieldset class="message">
                    <legend>
                        <h3>
                            <?php
                            //Jezeli sa bledy to wyswietlamy, jesli nie to
                            // wyświetlamy usera
                            if (!printErrors($errors)) {
                                echo 'Wyślij wiadomość do użytkownika:';
                                echo User::generateUserLinkById($conn, $uid);
                            }
                            ?>
                        </h3>
                    </legend>
                    <textarea class='input' type="text" rows="10" cols="50"
                              wrap="physical" name="message-text" 
                              placeholder="Treść wiadomości"></textarea>
                    <br>
                    <input class='button'  type="submit" value="Wyślij">
                </fieldset>
                <?php
                //Potwierdzenie wyświetla się 3 sekundy 
                if (isset($confirm)) {
                    printErrors($confirm);
                    header('refresh: 3;');
                }
                ?>
            </form>
        </main>

        <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>    
        </footer>
    </body>
</html>