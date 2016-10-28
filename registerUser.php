<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//jeśli user jest zalogowany to przekierowuję na główną
if (isset($_SESSION['loggedUser'])) {
    header("Location: index.php");
}
$errors = [];
//sprawdzam co user wpisał w formularz
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //sprawdzam przesłaną nazwę i trimuję
    if (isset($_POST['user-name']) && strlen(trim($_POST['user-name'])) > 0) {
        $userName = substr(trim($_POST['user-name']), 0, 20);
    } else {
        $errors[] = 'Podałeś nieprawidłowe imię użytkownika';
    }

    //sprawdzam przesłany e-mail, jego długość po usunięciu białych znaków
    if (isset($_POST['user-email']) && strlen(trim($_POST['user-email'])) > 5) {
        $userEmail = trim($_POST['user-email']);
        
        //sprawdzam czy mail jest już w bazie wbudowaną funkcją
        if (!User::emailIsAvailable($conn, $userEmail)) {
            $errors[] = " Podany email " . $userEmail . " jest już zajęty.";
        }
    } else {
        $errors[] = 'Podałeś nieprawidłowy e-mail';
    }

    //sprawdzam hasło, jego długość, obcinam białe znaki
    if (isset($_POST['user-password']) && strlen(trim($_POST['user-password'])) >= 5) {
        $userPassword = trim($_POST['user-password']);
        //sprawdzam czy hasło zgadza się w obydwu polach
        if (isset($_POST['user-confirm-password']) &&
                trim($_POST['user-confirm-password']) == $userPassword) {
            $userConfirmPassword = trim($_POST['user-confirm-password']);
        } else {
            $errors[] = 'Podane hasła nie zgadzają się';
        }
    } else {
        $errors[] = 'Podane hasło musi mieć co najmniej 5 znaków';
    }

    //Jeżeli wszystkie powyższe dane zwalidowały się poprawnie tworzymy nowego
    //usera, logujemy go i przekierowywujemy na główną.
    if (empty($errors)) {
        echo "Dane logowania są poprawne<br>";
        $newUser = new User;
        $newUser->setUsername($userName)->setEmail($userEmail)
                ->setPassword($userPassword)->saveToDB($conn);
        $_SESSION['loggedUser'] = $newUser->getId();
        header("Location: index.php");
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

        <!-----------Nagłówek z menu-------------->
        <header>
            <nav class="menu">
                <ul>
                    <li>
                        <a href="index.php">
                            <span><img src="gfx/ico/home.png"></span>
                            <span> Główna</span>
                        </a>
                    </li>
                    <li>
                        <a href="loginUser.php">
                            <span><img src="gfx/ico/log-in.png"></span>
                            <span> Zaloguj się</span>
                        </a>
                    </li>                    
                    <li id="li-left"><p>Zarejestruj się<p></li>
                </ul>
            </nav>
        </header>

        <!—-----------Panel boczny --------------->
        <aside>
            <section class="sidebar">
                <h3>Zaloguj się na Twittera!</h3>
            </section>
        </aside>

        <!—-----------Główna treść --------------->
        <main>
            <?php printErrors($errors); ?>
            <fieldset class="message">
                <legend><h3>Zarejestruj nowego użytkownika</h3></legend>
                <form action=# method="POST">
                    <label>
                        <p>Imię:</p> 
                        <input class='input-short' type="text" maxlenght="20"
                               name="user-name" placeholder="Wpisz imię (maksymalnie 20 znaków)">
                    </label>
                    <label>
                        <p>E-mail:</p>
                        <input class='input-short' type="email" maxlenght="30"
                               name="user-email" placeholder="Wpisz E-mail"><br>
                    </label>
                    <label>
                        <p>Hasło</p>
                        <input class='input-short' type="password" maxlenght="30"
                               name="user-password" placeholder="Podaj hasło (od 5 do 30 znaków)"><br>
                    </label>
                    <label>
                        <p>Powtórz hasło</p>
                        <input class='input-short' type="password" maxlenght="30"
                               name="user-confirm-password" placeholder="Powtórz hasło"><br>
                    </label>
                    <br>
                    <input class='button'  type="submit" value="Zarejestruj">
                </form>
            </fieldset>
        </main>

        <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>    
        </footer>
    </body>
</html>
<?php


