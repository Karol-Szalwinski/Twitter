<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//Ustalamy id i name zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Wiadomości</title>
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
                <h3>Otrzymane wiadomości:</h3>
                <?php
                if ($myAllRecipientMessages = Message::loadMessagesByRecipientId($conn, $loggedUserId)) {
                    foreach ($myAllRecipientMessages as $message) {
                        $message->showRecipientMessages($conn);
                    }
                } else {
                    echo 'Brak otrzymanych wiadomości';
                }
                ?>

                <h3>Wysłane wiadomości:</h3>
                <?php
                if ($myAllSentMessages = Message::loadMessagesBySenderId($conn, $loggedUserId)) {
                    foreach ($myAllSentMessages as $message) {
                        $message->showSendedMessages($conn);
                    }
                } else {
                    echo 'Brak wysłanych wiadomości';
                }
                
                ?>
            </div>
        </main>
        
        <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>   
        </footer>

    </body>
</html>