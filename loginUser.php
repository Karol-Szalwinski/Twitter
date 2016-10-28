<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//jeśli user jest zalogowany to przekierowuję na główną
if (isset($_SESSION['loggedUser'])) {
    header("Location: index.php");
}
$errors = [];

//sprawdzam czy został przesłany e-mail i hasło
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['e-mail']) && isset($_POST['password'])) {
        $email = $_POST['e-mail'];
        $password = $_POST['password'];

        //logowanie przesłanym mailem i hasłem
        if ($userId = User::loginUser($conn, $email, $password)) {
            $_SESSION['loggedUser'] = $userId;
            header("Location: index.php");
        } else {
            $errors[] = 'Niepoprawne dane logowania';
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log In</title>
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
                        <a href="registerUser.php">
                            <span><img src="gfx/ico/register.png"></span>
                            <span> Załóż konto</span>
                        </a>
                    </li>
                    <li id="li-left"><p>Zaloguj się<p></li>
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
            <!-Tutaj wyświetlam błędy-->
            <?php printErrors($errors); ?>
            <form action=# method='POST'>
                <fieldset class="message">
                    <legend><h3>Zaloguj się</h3></legend>
                    <label>
                        <p>E-mail:</p>
                        <input class='input-short' type="text" name="e-mail" placeholder="e-mail">
                    </label>
                    <label>
                        <p>Password:</p>
                        <input class='input-short' type="password" name="password" placeholder="wprowadź hasło">
                    </label>
                    <br><br>
                    <input class='button' type="submit" value="Zaloguj mnie">
                    </form>

                </fieldset>
                <fieldset class="message">
                    <legend><h3>Nie masz jeszcze konta?</h3></legend>
                    <form action="logoutUser.php">
                        <input class='button'  type="submit" value="Zarejestruj się" formaction="registerUser.php">
                    </form>
                </fieldset>
        </main>
        <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>    
        </footer>
    </body>
</html>





