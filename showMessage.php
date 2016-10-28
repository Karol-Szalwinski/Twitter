<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//Ustalamy id i name zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();
$errors=[];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Wiadomość</title>
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
            <h3>Wiadomość:</h3>
            <?php
            //sprawdzam czy weszliśmy metodą get i czy podaliśmy prawidłowy nr usera
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if (isset($_GET['mid']) && is_numeric($_GET['mid'])) {
                    $mid = $_GET['mid'];
                    //Jeśli jest w bazie wiadomość
                    if ($message = Message::loadMessageById($conn, $mid)) {
                        //Jeśli mamy uprawnienia do niej
                        if ($message->getSenderId() == $loggedUserId ||
                                $message->getRecipientId() == $loggedUserId) {
                            //identyfikuję odbiorcę, nadawcę i ustalam treść
                            $senderName = User::loadUserById($conn, $message->getSenderId())->getUsername();
                            $recipientName = User::loadUserById($conn, $message->getRecipientId())->getUsername();
                            $messageText = nl2br($message->getMessage());
                            //wyświetlam treść
                            echo "<div class='message'><h4> Od ";
                            echo "<a href='showUser.php?uid=" . $message->getSenderId() . "'>$senderName</a>";
                            echo " do ";
                            echo "<a href='showUser.php?uid=" . $message->getRecipientId() . "'>$recipientName</a>";
                            echo "</h4><h4>Treść:<br> $messageText" . "</h4><h4> Z dnia: ";
                            echo "{$message->getCreationDate()} ";
                            echo "</h4></div>";
                        } else {
                            $errors[] =  "Grrr... Próbujesz przeczytać nie swoją wiadomość! Nieładnie!";
                        }
                        if ($message->getRecipientId() == $loggedUserId) {
                            $message->setRead()->saveToDB($conn);
                        }
                    } else {
                        $errors[] = 'Nie ma tej wiadomości w bazie';
                    }
                } else {
                    $errors[] = 'Oj coś kombinujesz z adresem strony... Nieładnie!';
                }
            }
            printErrors($errors);
            ?>
        </main>

        <!—--------------Stopka------------------->
        <footer class="footer">
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>   
        </footer>
    </body>
</html>